 
/*
* Show tool tip on position top +20 , left. 
* tip_id is id ov DIV that shall be shown
*
*/ 
 
function ShowToolTip(top, left, tip_id){
    try{
		if(document.getElementById){
			var tooltip =  document.getElementById(tip_id);		
			tooltip.style.display = '';		
			tooltip.style.visibility = 'visible'; 
			tooltip.style.top = top +  20;
			tooltip.style.left = left + 10;		
			}
	}catch(e){}	
}

/**
* Hide DIV with this ID = tip_id
*
*/	
function HideToolTip(tip_id){
    try{
		if(document.getElementById){
			var tooltip =  document.getElementById(tip_id);
			tooltip.style.display = 'none';
			tooltip.style.visibility = 'hidden'; 		
			}
		}catch(e){
	}
	
}




/*
* Get absolute Top of this element
*
*/
function GetTop( oElement )
{
    try{
    var iReturnValue = 0;
    while( oElement != null ) {
        iReturnValue += oElement.offsetTop;
        oElement = oElement.offsetParent;
    }
    return iReturnValue;
    }catch(e){
    return 0;
    }
}


/*
* Get absolute Left of this element
*
*/
function GetLeft( oElement )
{
    try{
    var iReturnValue = 0;
    while( oElement != null ) {
        iReturnValue += oElement.offsetLeft;
        oElement = oElement.offsetParent;
    }
    return iReturnValue;
    }catch(e){
    return 0;
    }
}

