
function cities(){
  xmlhttp = new XMLHttpRequest();
  xmlhttp.open('GET', 'http://77.121.192.25/qgis-ck/php/links.php', false);
  
  xmlhttp.send();
  if (xmlhttp.status != 200) {
        // обработать ошибку
        alert('Ошибка ' + xmlhttp.status + ': ' + xmlhttp.statusText);
      } else {
        // вывести результат
        return all_cities = JSON.parse(xmlhttp.responseText);
      }
};
cities();

//console.log('all_cities', all_cities);
$(document).ready(function(){

 //$('#result').empty();
      var info=[];
      var html=$('#template').html();
      //console.log('parsedResponse',parsedResponse);
      info = all_cities;//info.push(all_cities);
      //console.log('info',info);
      content = tmpl(html, {
          data: info

        });
      
      //console.log('content', content);
      $('#result').append(content);

    
});