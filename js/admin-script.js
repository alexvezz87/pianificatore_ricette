/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function($){   
    var countIng = 1;
    
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
    

});
