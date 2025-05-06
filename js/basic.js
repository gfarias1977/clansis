function valida_rut( rut )
{
	if (rut=="") {
		return 0;
	} else {
		real_dig = rut.substr(rut.length-1,1);
		if (real_dig=="K" || real_dig=="k") {
			in_dig=10;
		} else {
			in_dig=Number(real_dig);
		}
		j = rut.length-2;
	    nSum = 0;
	    nNum = 2;
	    while (j>0) {
	        xdig = rut.substr(j-1, 1);
	        if (xdig != ".") {
	            nSum = nSum + Number(xdig) * nNum;
	            nNum = nNum + 1;
	            if (nNum > 7) {
		            nNum = 2;
	            }
	        }
	        j = j - 1;
	    }

	    out_dig = (11 - (nSum - Math.floor(nSum / 11) * 11));

	    if (out_dig == 11) {
		    out_dig = 0;
	    }

	    if (out_dig == in_dig) {
	        return 0;
	    } else {
	        return 1;
	    }
    }
}
function check_number(numero){
    var arrNumero;
    
    if(numero.indexOf(",") != -1 || numero.indexOf(".") != -1){
        if(numero.indexOf(",") != -1){
            if(numero.indexOf(",") != numero.lastIndexOf(",")) return false;
            arrNumero = numero.split(",");
        }
        else{
            if(numero.indexOf(".") != numero.lastIndexOf(".")) return false;
            arrNumero = numero.split(".");
        }
        if(isNaN(arrNumero[0]) || isNaN(arrNumero[1])) return false;
    }
    else{
        if(isNaN(numero)) return false;
    }
    return true;
}
function isValidDate(date) {
        var valid = true;

        date = date.replace('/-/g', '');

        var month = parseInt(date.substring(0, 2),10);
        var day   = parseInt(date.substring(2, 4),10);
        var year  = parseInt(date.substring(4, 8),10);

        if((month < 1) || (month > 12)) valid = false;
        else if((day < 1) || (day > 31)) valid = false;
        else if(((month == 4) || (month == 6) || (month == 9) || (month == 11)) && (day > 30)) valid = false;
        else if((month == 2) && (((year % 400) == 0) || ((year % 4) == 0)) && ((year % 100) != 0) && (day > 29)) valid = false;
        else if((month == 2) && ((year % 100) == 0) && (day > 29)) valid = false;

    return valid;
}