/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function($){   
    
    $('.aggiungi-ricetta a').click(function(){
        var countRicetta = $(this).parent('.aggiungi-ricetta').siblings('.lista-ricette').find('.nome-ricetta').size();
        var $element = $(this).parent('.aggiungi-ricetta').siblings('.lista-ricette').find('.nome-ricetta:first-child').clone();
        
        countRicetta++;
        var temp =  $element.find('select').attr('name').split('-');
        var newName = "";
        for(var i=0; i < temp.length; i++){
            if(i == temp.length -1 ){
                newName+=countRicetta;
            }
            else{
                newName+=temp[i]+'-';
            }
        }
        //cambio il name del nuovo input
        $element.find('select').attr('name', newName);
        
        //pulisco i valori
        $element.find('select').val('');
        
        $element.appendTo($(this).parent('.aggiungi-ricetta').siblings('.lista-ricette'));
        
        
    });
    
    //ASCOLTATORE SULLA RICERCA
    $(document).on('click', '.container-ricerca .nome-ingrediente .suggerimenti li', function(){
        var value = $(this).text();
        
        
        var lista = $('.container-ricerca .nome-ingrediente input[name=lista-ingredienti]').val();
       
        if(lista === ''){
            $('.container-ricerca .nome-ingrediente input[name=lista-ingredienti]').val(value);
        }
        else{
            var temp = lista.split(',');
            var trovato = false;
            for(var i=0; i < temp.length; i++){
                if(value == temp[i]){
                    trovato = true;
                }
            }
            if(trovato == false){
                lista = lista+','+value;
                $('.container-ricerca .nome-ingrediente input[name=lista-ingredienti]').val(lista);
            }
        }
        
        $('.container-ricerca .nome-ingrediente input[name=nome-ingrediente]').val('');
       
    });
    
    $('.container-ricerca input[name=cancella-ingredienti]').click(function(){
        $('.container-ricerca .nome-ingrediente input[name=lista-ingredienti]').val('');
    });
    
    
    //AGGIUNGI RICETTA AL SELEZIONATORE
    $(document).on('click', '.add-recipe', function(){
        var id = $(this).siblings('input[name=id-r]').val();
        var nome = $(this).siblings('input[name=nome-r]').val();
        
        aggiungiRicettaAlSelezionatore(id, nome);
        
        //visualzzo il selezionatore
        $('#selezionatore-ricette').show();
        
    });
    
    //RIMUOVI RICETTA DAL SELEZIONATORE
    $(document).on('click', '.remove-recipe', function(){
        
        //tolgo la ricetta dall'agenda
        var id = $(this).siblings('input[name=id-r]').val();
        $('form.agenda-container .lista-ricette .nome-ricetta select option.recipe-'+id).remove();
        
        //la tolgo dal selezionatore
        $(this).parent('.ricetta').remove();
    });
    
    //CANCELLA SELEZIONATORE
    $('#selezionatore-ricette .cancella-lista').click(function(){
        $('#selezionatore-ricette .lista .ricetta').remove();
        //Pulisco le select
        $('form.agenda-container .lista-ricette .nome-ricetta select').html('<option value=""></option>');
        $('.pianificatore-ricette').hide();
    });
    
    //AGGIUNGI CAMPI AI PASTI DELL'AGENDA
    $('#selezionatore-ricette .prosegui-agenda').click(function(){
        
        if($('#selezionatore-ricette .lista .ricetta').size() > 0){
           //visualizzo il pianificatore
           $('.pianificatore-ricette').show();
           
        }
        else{
            alert('Non hai ricette per l\'agenda!');
        }
        
    });
    
    
    //CHECK delle ricette nei pasti
    $(document).on('change', '.pasto .lista-ricette select', function(){
        var currentVal = $(this).val();
        
        //controllo all'interno dello stesso pasto
        var $listaRicette = $(this).parent('.nome-ricetta').siblings('.nome-ricetta');
        var trovatoInPasto = false;
        $listaRicette.each(function(){
            if($(this).find('select').val() == currentVal){
                trovatoInPasto = true;
            }            
        });
        
        if(trovatoInPasto == true){
            alert('Attenzione! Hai indicato ricette uguali per lo stesso pasto!');
            //lo elimino 
            $(this).parent('.nome-ricetta').remove();
        }
        
        //controllo all'interno dello stesso giorno
        var trovatoInGiorno = false;
        var $giorno = $(this).parent('.nome-ricetta').parent('.lista-ricette').parent('.pasto').parent('.giorno-agenda');
        
        var counter = 0;
        $giorno.find('select').each(function(){
            if($(this).val() == currentVal){
                counter++;
                if(counter > 1){
                    trovatoInGiorno = true;
                }
            }
        });
        
        if(trovatoInGiorno == true){
            alert('Attenzione! Hai indicato ricette uguali per lo stesso giorno!');
        }
    })
    
    
    //RIMUOVI RICETTA DAL PASTO
    $(document).on('click', '.remove-from-pasto', function(){
        $(this).parent('.nome-ricetta').remove();
    });
    
    $('button.ricerca-ricette').click(function(){
        //visualizzo il loader
        $('.loader-container').show();
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: $('input[name=ajax-url]').val(),
            data: {
                action: 'ricerca_ricette',
                mode: $('input[name=mode]').val(),
                nomeRicetta: $('input[name=nome-ricetta]').val(),
                tipologia: $('select[multiple]').val(),
                ingredienti: $('input[name=lista-ingredienti]').val()
            },
            success: function(data){
                printTabellaRisultati(data);
                $('.loader-container').hide();
            },
            error: function(){
                alert('error');
                $('.loader-container').hide();
            }
        });
    });
    
    
    function printSingolaTabellaDesktop(data, url, counter, tot){
        
        var html = "";        
        html+='<table class="table table-striped hidden-xs tabella-risultati" data-tabella="'+counter+'">';
        html+='<thead>';
        html+='<tr><th colspan="2">Ricetta</th><th>Caratteristiche</th><th>Preparazione</th><th></th></tr>';
        html+='</thead>';
        html+='<tbody>';
        
        for(var i=0; i < data.length; i++){
                
            html+='<tr>';
            //nome
            var urlRicetta = url+'/ricetta?id='+data[i].ID;
            html+='<td>';
            html+='<a class="title-ricetta" target="_blank" href="'+urlRicetta+'">';
            html+='<img class="image-ricetta" src="'+data[i].foto+'" title="'+data[i].nome+'" />';
            html+='</a>';
            html+='</td>'
            html+='<td>';
            html+='<a class="title-ricetta" target="_blank" href="'+urlRicetta+'">'+data[i].nome+'</a>';
            html+='</td>'

            //tipologie
            //alert(data[i].tipologie[0]);
            html+='<td>'+data[i].tipologie+'</td>';

            //durata
            html+='<td>'+data[i].durata+' minuti</td>';                

            //azioni
            html+='<td>';
            html+='<a class="add-recipe"> Ricetta</a>';
            html+='<input type="hidden" name="id-r" value="'+data[i].ID+'">';
            html+='<input type="hidden" name="nome-r" value="'+data[i].nome+'">';
            html+='</td>';


            html+='</tr>';
        }
        html+='<tr class="pag-info"><td colspan="5"><p>Pagina '+(counter+1)+' di '+tot+'</p></td></tr>'
        html+='</tbody>';
        html+='</table>';
        
        return html;
    }
    
    function printRisultatiMobile(data, url, counter, tot){
        
        var html = "";
        
        html+='<div class="container-gruppo-ricette-mobile" data-gruppo="'+counter+'" >';
        for(var i=0; i < data.length; i++){                
            var urlRicetta = url+'/ricetta?id='+data[i].ID;                
            html+='<div class="ricetta">';
            html+='<a class="add-recipe">Aggiungi Ricetta</a>';
            html+='<input type="hidden" name="id-r" value="'+data[i].ID+'">';
            html+='<input type="hidden" name="nome-r" value="'+data[i].nome+'">';
            html+='<a class="title-ricetta" target="_blank" href="'+urlRicetta+'">';
            html+='<img class="image-ricetta" src="'+data[i].foto+'" title="'+data[i].nome+'" />';
            html+='</a>';
            html+='<a class="title-ricetta" target="_blank" href="'+urlRicetta+'">'+data[i].nome+'</a>';
            html+='<p class="info">'+data[i].tipologie+'</p>';
            html+='<p class="info">'+data[i].durata+' minuti</p>';
            html+='</div>';
        }
        
        html+='</div>';
        
        return html;
    }
    
    function printTabellaRisultati(data){
        var url = $('input[name=url-home]').val();
        var risultatiVisibili = 10;
        var html = "";
        if(data != null){
            html+='<h3 class="titolo">Risultati ricerca</h3>'
            
            html+='<div class="carosello-risultati hidden-xs">';        
            //Lo scopo e creare uno slider di tabelle per visualizzare più risultati
            var tabelle = [];
            var count = 1;
            var risultati = [];
            //divido i risultati in tante tabelle da stampare
            for(var i=0; i < data.length; i++){                
                risultati.push(data[i]);                
                if(count % risultatiVisibili == 0 || i == data.length-1){
                    tabelle.push(risultati);
                    risultati = [];
                }
                count++;
            }
            
            //console.log(tabelle);
            
            for(var i=0; i< tabelle.length; i++ ){
                html+= printSingolaTabellaDesktop(tabelle[i], url, i, tabelle.length);               
            }
            
            if(data.length > risultatiVisibili ){
                html+='<div class="arrows">';            
                html+='<div class="indietro"></div><div class="avanti"></div>';
                html+='</div>';
               
            }
             html+='</div>';
            
            
            
            //versione mobile
            html+='<div class="container-risultati-mobile col-xs-12 visible-xs">';
                        
            for(var i=0; i < tabelle.length; i++){
                html+= printRisultatiMobile(tabelle[i], url, i, tabelle.length);
            }
            
            if(data.length > risultatiVisibili ){
                html+='<div class="arrows-mobile"></div>';
                html+='</div>';
            }
            
            html+='</div>';
        }
        else{
            html = "<p>Nessuna ricetta corrisponde ai criteri di ricerca.</p>"
        }
        
        $('.container-risultati').html(html);
    }
    
    //ASCOLTATORE CARICA TEMPLATE
    $('#ricerca-template .carica-template').click(function(){        
        var idTemplate = $(this).parent('div').parent('#ricerca-template').find('select').val();
        if(idTemplate != ''){
            //visualizzo il loader
            $('.loader-container').show();
            
            //devo caricare le ricette di quel template
            //ottengo le ricette
            
            $.ajax({
            type: 'POST',
            dataType: 'json',
            url: $('input[name=ajax-url]').val(),
            data: {
                action: 'get_ricette_template',
                idTemplate: idTemplate
            },
            success: function(data){
               
                //Pulisco il selezionatore 
                $('#selezionatore-ricette .lista').html(''); 
                //Pulisco le select
                $('form.agenda-container .lista-ricette .nome-ricetta select').html('<option value=""></option>');
                
                //popolo il selezionatore
                for(var key in data.ricette){
                     aggiungiRicettaAlSelezionatore(key, data.ricette[key]);
                }
               
                //attribuisco un valore ad ogni select
                for(var key in data.pasti){
                    //console.log(data.pasti[key]);
                    var giorno = data.pasti[key].giorno;
                    for(var key3 in data.pasti[key].pasti){
                        var pasto = data.pasti[key].pasti[key3].pasto;
                        var ricetta = data.pasti[key].pasti[key3].ricetta;
                        //console.log('giorno: '+giorno+' pasto: '+pasto+' ricetta: '+ricetta);
                        $('.giorno-agenda.day-'+giorno+' .pasto-'+pasto+' .lista-ricette .nome-ricetta:first-child select option').each(function(){
                            if($(this).val()==ricetta){
                                //aggiungo l'attributo select
                                $(this).attr('selected','selected');
                            }
                        });                        
                    }                  
                }                
                                
                //visualizzo il selezionatore ricette
                $('#selezionatore-ricette').show();
                
                //tolgo il loader
                $('.loader-container').hide();
            },
            error: function(){
                alert('error');
                //tolgo il loader
                $('.loader-container').hide();
            }
        });
            
        }
        else{
            alert('Scegli un template!');
        }
    });
    
    function printRigaSelezionatore(id, nome){
        var html = '<div class="ricetta">';
        html += '<input type="hidden" name="id-r" value="'+id+'" />';
        html += '<input type="hidden" name="nome-r" value="'+nome+'" />';
        html += '<p>'+nome+'</p><a title="Rimuovi Ricetta" class="remove-recipe"></a>';
        html += '<div class="clear"></div></div>';
        
        return html;
    }
    
    function aggiungiRicettaAlSelezionatore(id, nome){
        var html = printRigaSelezionatore(id, nome);
        
        //controllo se esiste già la ricetta in questione nel selezionatore
        var trovato = false;
        $('#selezionatore-ricette .lista .ricetta').each(function(){
            if($(this).find('input[name=id-r]').val() == id){
                trovato = true;
            }
        });
        
        if(trovato == false){
            $(html).appendTo($('#selezionatore-ricette .lista'));
            //aggiungo direttamente i valori anche all'agenda
            var recipe = '<option class="recipe-'+id+'" value="'+id+'">'+nome+'</option>';
            $(recipe).appendTo($('form.agenda-container .lista-ricette .nome-ricetta select'));
            
        }        
    }
    
    //SISTEMO LA LUNGHEZZA DELLE CELLA NELL'AGENDA DETTAGLIO
    if($('.container-agenda-public').size()>0){
        var nTp = $('.container-tp .tp').size(); 
        $('.tp').css('width', 100/nTp+'%');
    }
    
    //SISTEMO L'ALTEZZA DELLE CELLE NELL'AGENDA DETTAGLIO
    if($('.container-agenda-public').size()>0){
        
        $('.giorno').each(function(){
            var max = 49;
            $(this).find('.tp').each(function(){
                
                if($(this).height() > max){
                    max = $(this).height();
                }
            });
            
            if(max > 49){
                $(this).find('.tp').css('height', (max+15)+'px');
                
            }
            
        });
    }
    
    //ASCOLTATORE SU VISUALIZZAZIONE RISULTATI RICERCA
    //AVANTI
    $(document).on('click', '.arrows .avanti', function(){
        var currentTable = $('.tabella-risultati:visible').data('tabella');
        var maxValueTable = $('.tabella-risultati').size(); 
        if(currentTable < maxValueTable -1 ){
            //vado avanti
            $('.tabella-risultati[data-tabella="'+currentTable+'"').hide();
            $('.tabella-risultati[data-tabella="'+(currentTable+1)+'"').css('display', 'table');
        }        
    });
    //INDIETRO
    $(document).on('click', '.arrows .indietro', function(){
        var currentTable = $('.tabella-risultati:visible').data('tabella');        
        if(currentTable > 0 ){
            //vado avanti
            $('.tabella-risultati[data-tabella="'+currentTable+'"').hide();
            $('.tabella-risultati[data-tabella="'+(currentTable-1)+'"').css('display', 'table');
        }        
    });    
    //MORE
    $(document).on('click', '.arrows-mobile', function(){
        var currentGruppo = $('.container-gruppo-ricette-mobile:visible:last').data('gruppo');
        var maxValueGruppo = $('.container-gruppo-ricette-mobile').size();
        console.log(currentGruppo);
        $('.container-gruppo-ricette-mobile[data-gruppo="'+(currentGruppo+1)+'"]').fadeIn();
        if(currentGruppo == maxValueGruppo - 2){
            $(this).hide();
        }
    });
    
    //open e close del box selezionatore ricette
    $('.oc-button').click(function(){
        //prendo la misura del box
        var widthBox = $('#selezionatore-ricette').width()+21+25;
        
        if($(this).hasClass('open')){
           //chiudiamo
           $('#selezionatore-ricette').animate({
               marginRight: '-='+widthBox+'px'
           }, 500);
           
            $(this).removeClass('open');
            $(this).addClass('close');
        }
        else if($(this).hasClass('close')){
           //apriamo
           $('#selezionatore-ricette').animate({
               marginRight: '+='+widthBox+'px'
           }, 500);
            $(this).removeClass('close');
            $(this).addClass('open');
        }
        
    });    
    
    
});

jQuery(document).ready(function($){   
    var countIng = $('.ingrediente').size();
    
    $('.add-ingrediente a').click(function(){
        var $element = $('.lista-ingredienti .ingrediente:first-child').clone();
        
        countIng++;
        var nameQt = 'r-ingrediente-qt-'+countIng;
        var nameUm = 'r-ingrediente-um-'+countIng;
        var nameIng = 'r-ingrediente-nome-'+countIng;
        
        //cambio il nome dell'input
        $element.find('.qt input').attr('name', nameQt);
        $element.find('.um input').attr('name', nameUm);
        $element.find('.nome-ingrediente input').attr('name', nameIng);
        
        //cambio l'id dell'input
        $element.find('.qt input').attr('id', nameQt);
        $element.find('.um input').attr('id', nameUm);
        $element.find('.nome-ingrediente input').attr('id', nameIng);
        
        //cambio il for della label
        $element.find('.qt label').attr('for', nameQt);
        $element.find('.um label').attr('for', nameUm);
        $element.find('.nome-ingrediente label').attr('for', nameIng);
        
        //pulisco i valori
        $element.find('.qt input').val('');
        $element.find('.um input').val('');
        $element.find('.nome-ingrediente input').val('');
        
        $element.appendTo('.lista-ingredienti');
    });
    
    //controllo l'elemento rimuovi ingrediente
    
    
    $(document.body).on('click', '.rimuovi-ingrediente a', function(){
        $(this).parent('.rimuovi-ingrediente').parent('.ingrediente').remove();
    });
    

});
