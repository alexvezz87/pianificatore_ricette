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
        var temp =  $element.find('input').attr('name').split('-');
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
        $element.find('input').attr('name', newName);
        
        //pulisco i valori
        $element.find('input').val('');
        
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
                alert(data);
            },
            error: function(){
                alert('error');
            }
        });
    });
    
});