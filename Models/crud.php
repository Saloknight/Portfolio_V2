<?php


     function getUsers(){
          return json_decode( 
               file_get_contents('https://api.escuelajs.co/api/v1/users'),
               true
           );
     }

     function create(){

     }

     function read(){
        
     }

     function update(){
        
     }

     function delete(){
        
     }