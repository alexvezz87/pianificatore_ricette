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
    
});