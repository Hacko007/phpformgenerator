    function makeRequest(element_id , url) {
        var http_request = false;

        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType("text/xml");
                // See note below about this line
            }
            
        } else if (window.ActiveXObject) { // IE
            
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }



        if (!http_request) {
            // alert("Giving up :( Cannot create an XMLHTTP instance");
            return false;
        }
        
        http_request.onreadystatechange = function() { alertContents(element_id,http_request); };
        http_request.open("GET", url, true);
        http_request.send(null);

    }


    function alertContents(element_id,http_request) {
			
         try {
         	
         	var  msg  = document.getElementById(element_id);
         	if (http_request.readyState == 1) {
         		     	msg.innerHTML =  "[loading......]";
            }else if (http_request.readyState == 4) {
                if (http_request.status == 200) {
                    	msg.innerHTML =  http_request.responseText;
                    	
                    	/*
                    	var xmldoc = http_request.responseXML;
                    	
				            	if(xmldoc.getElementsByTagName("root").length > 0){
												var root_node = xmldoc.getElementsByTagName("root").item(0);
												msg.innerHTML = root_node.firstChild.data ;
											}else{
												msg.innerHTML = "no data" ;
											}
										*/
                } else {
                    msg.innerHTML = "There was a problem with the request.";
                }
            }
        }
        catch( e ) {
             //msg.innerHTML = "Caught Exception: " + e.description;
        }

    }
    
    
    /*  
    * SELECT tag fill functions
    *
    */
    
    
    function makeSelectRequest(element_id , url) {
    			
    		 var errormsg= document.getElementById('errormsg');    			
    	try{
    		
        var http_request = false;
       
        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType("text/xml");
                // See note below about this line
            }
            
        } else if (window.ActiveXObject) { // IE
            
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
            		errormsg.innerHTML = "Caught Exception: " + e.description;     
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                		errormsg.innerHTML = "Caught Exception: " + e.description;     
                	}
            }
          }

        if (!http_request) {            
            return false;
        }
        
        http_request.onreadystatechange = function() { alertSelectContents(element_id,http_request); };
        http_request.open("GET", url, true);
        http_request.send(null);
 				
 			 }catch(e){
         	errormsg.innerHTML = "Error:" +  e.description;
      }   
        
    }


    function alertSelectContents(element_id,http_request) {
				 var errormsg= document.getElementById('errormsg');
				 
         try {
         	
         	var  select_elem  = document.getElementById(element_id);
         	
         	while(select_elem.length > 0 ){
         			select_elem.remove(0);
         		}
         		
         	if (http_request.readyState == 1) {
         					
         		     	select_elem.options[0] = new Option("[loading......]","[loading......]") ;
         		     	
          }else if (http_request.readyState == 4) {
                if (http_request.status == 200) {
                		                    	
                    	var ops = http_request.responseText.split('\n');
                    	for(var i = 0 ; i < ops.length; i++){
                    		select_elem.options[i] = new Option(ops[i],ops[i]) ;
                    	}
                    
                }
         }
        }
        catch( e ) {
             errormsg.innerHTML = "Caught Exception: " + e.description;             
        }

    }
