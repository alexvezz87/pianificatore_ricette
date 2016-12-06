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
        
        var html = '<div class="ricetta">';
        html += '<input type="hidden" name="id-r" value="'+id+'" />';
        html += '<input type="hidden" name="nome-r" value="'+nome+'" />';
        html += '<p>'+nome+'</p><a class="remove-recipe"></a>';
        html += '<div class="clear"></div></div>';
        
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
    });
    
    //AGGIUNGI CAMPI AI PASTI DELL'AGENDA
    $('#selezionatore-ricette .prosegui-agenda').click(function(){
        
        if($('#selezionatore-ricette .lista .ricetta').size() > 0){
            /*
            var html='<option value=""></option>';
            $('#selezionatore-ricette .lista .ricetta').each(function(){
                var id = $(this).find('input[name=id-r]').val();
                var nome = $(this).find('input[name=nome-r]').val();
                html+='<option value="'+id+'">'+nome+'</option>';
            });
            $('.lista-ricette .nome-ricetta select').html(html);
            */
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
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: $('input[name=ajax-url]').val(),
            data: {
                action: 'ricerca_ricette',
                nomeRicetta: $('input[name=nome-ricetta]').val(),
                tipologia: $('select[multiple]').val(),
                ingredienti: $('input[name=lista-ingredienti]').val()
            },
            success: function(data){
                printTabellaRisultati(data);
            },
            error: function(){
                alert('error');
            }
        });
    });
    
    function printTabellaRisultati(data){
        
        var html = "";
        if(data != null){
            html+='<h3>Risultati ricerca</h3>'
            html+='<table class="table table-striped">';
            html+='<thead>';
            html+='<tr><th>Ricetta</th><th>Caratteristiche</th><th>Tempo preparazione</th><th></th></tr>';
            html+='</thead>';
            html+='<tbody>';
            
            var url = $('input[name=url-home]').val();
            
            for(var i=0; i < data.length; i++){
                html+='<tr>';
                //nome
                var urlRicetta = url+='/ricetta?id='+data[i].ID;
                html+='<td><a target="_blank" href="'+urlRicetta+'">'+data[i].nome+'</a></td>';
                
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
            
            html+='</tbody>';
            html+='</table>';
        }
        else{
            html = "<p>Nessuna ricetta corrisponde ai criteri di ricerca.</p>"
        }
        
        $('.container-risultati').html(html);
    }
    
});

