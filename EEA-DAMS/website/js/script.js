/**
 * EEA-DAMS 
 *
 * @abstract	 Javascript function.
 * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
 * @copyright    2005
 * @version    	 1.0
 *
 */
 
 
var xOp7Up,xOp6Dn,xIE4Up,xIE4,xIE5,xNN4,xUA=navigator.userAgent.toLowerCase();
if(window.opera){
  var i=xUA.indexOf('opera');
  if(i!=-1){
    var v=parseInt(xUA.charAt(i+6));
    xOp7Up=v>=7;
    xOp6Dn=v<7;
  }
}
else if(navigator.vendor!='KDE' && document.all && xUA.indexOf('msie')!=-1){
  xIE4Up=parseFloat(navigator.appVersion)>=4;
  xIE4=xUA.indexOf('msie 4')!=-1;
  xIE5=xUA.indexOf('msie 5')!=-1;
}
else if(document.layers){xNN4=true;}
xMac=xUA.indexOf('mac')!=-1;


	function validformuser(myform){
		if (document.getElementById("userpassword1").value!=document.getElementById("userpassword2").value)
		{	alert ("Check your password.");
			return;
		}
		if (document.getElementById("userlogin").value=="")
		{	alert ("Login is mandatory.");
			return;
		}
			
		myform.submit();
	}

	function saveDams(myform, lst) {
		for (i=lst.options.length-1; i>-1; i--){
				lst.options[i].selected = true;
		}
		myform.submit ();
	}

	function removeAllOptions (myform){
		for (i=myform.options.length-1; i>-1; i--){
					myform.remove (myform.options[i]);
		}
	}
	
	// TODO : could check if element exist in the out list
	function affecte(aff,unaff)							{
		sel=unaff.options.selectedIndex;				
		if (sel != -1)						
		{
			aff_txt = unaff.options[sel].text;			
			aff_val = unaff.options[sel].value;		
			aff_opt = new Option(aff_txt,aff_val,1,0);	
										
 										
			aff.options[aff.options.length] = aff_opt;	
			unaff.options[sel] = null;				
		}
		else
		{
			window.alert("Select one element please");
		}
	}
		
	function affectetout(aff,unaff)							
	{
		ind=(unaff.options.length);
	
		for (a = 0; a < ind; a += 1)
		{
			sel=unaff.options.selectedIndex;
			if (sel != -1)
			{
				aff_txt = unaff.options[sel].text;
				aff_val = unaff.options[sel].value;
				aff_opt = new Option(aff_txt,aff_val,1,0);
				aff.options[aff.options.length] = aff_opt;
				unaff.options[sel] = null;
			}	
		}
	}




///
/// Fonctions générales de manipulation de <SELECT>
///

function getIndexByValue(select,value) {
	nb = select.options.length;
	indexFound = -1;
	i = 0;
	while(i<nb && indexFound == -1) {
		if(select.options[i].value == value)
			indexFound = i;
		else i++;
	}
	return indexFound;
}

function selectByValues() {
	values = selectByValues.arguments;
	if(values.length == 0) return;
	select = values[0];
	for(s=1;s<values.length;s++) {
		if(values[s] != null)
			index = getIndexByValue(select,values[s]);
				if(index != -1)
					select.options[index].selected = true;
	}
}

function getNbSel(select) {
	nb = select.options.length;
	nbsel = 0;
	for(i=0;i<nb;i++)
		if(select.options[i].selected == true)
			nbsel++;
	return nbsel;
}

function getSelection(select) {
	j = 0;
	nb = select.options.length;
	listeObj = new Array();
	for(i=0;i<nb;i++)
		if(select.options[i].selected == true) {
			listeObj[j] = select.options[i].value;
			j++;
		}
	return listeObj;
}

function getAll(select) {
	nb = select.options.length;
	listeObj = new Array();
	for(i=0;i<nb;i++)
		listeObj[i] = select.options[i].value;
	return listeObj;
}

function unselAllOptions(select) {
	nb = select.options.length;
	for(i=0;i<nb;i++)
		select.options[i].selected = false;
}


/////////////////////////////////////
// Gestion des listes communicantes
//////////////////////////////////

function compareElements(element1,element2) {
	if(element2.value > element1.value) result = -1; else result = 1;
	return result;
}
function element(value,text,selected) {
   this.value = value;
   this.text = text;
   this.selected = selected;
   return this;
}

function initListeVariables(objVar,objListe) {
	nbobj = objListe.length;
	for(i=0;i<nbobj;i++)
		objVar[i] = new element(objListe[i].value,objListe[i].text,objListe[i].selected);
}

function majSel(objVar,objListe) {
	nbobjliste = objListe.length;
	for(i=0;i<nbobjliste;i++) {
		selObj(objVar,objListe[i].value,objListe[i].selected);

		// a-t-on selectionné une famille ?
		if (objListe[i].selected && (objListe[i].value.indexOf("FA") >= 0))
		{	//alert("famille selectionnée : "+objListe[i].value);
			for(j=i+1;j<nbobjliste;j++)
			{	//alert("sous-groupe selectionné : "+objListe[j].value+" ?");
					
				// est-ce un sous-groupe ?
				if (objListe[j].value.indexOf("GP") >= 0)
				{	//alert("sous-groupe selectionné : "+objListe[j].text);
					
					// on sélectionne le sous-groupe
					objListe[j].selected = true;
					selObj(objVar, objListe[j].value, true);
				}
				else
				{	break;
				}
			}
		}
	}
}

function majSelAllObjs(objVar,objListe) {
	nbobjlisteall = objListe.length;
	for(i=0;i<nbobjlisteall;i++) {
		selObj(objVar,objListe[i].value,true);
	}
}

function majUnselAllObjs(objVar,objListe) {
	nbobjlisteall = objListe.length;
	for(i=0;i<nbobjlisteall;i++) {
		selObj(objVar,objListe[i].value,false);
	}
}

function majListesObjs(objVar,objListe1,objListe2) {
	nbvar = objVar.length;
	objListe1.length = 0;
	objListe2.length = 0;
	j = 0;
	k = 0;
	for(i=0;i<nbvar;i++) {
		elt = new Option(objVar[i].text, objVar[i].value, false, false);
		if(objVar[i].selected == true) {
			objListe2[j] = elt;
			j++;
		} else {
			objListe1[k] = elt;
			k++;
		}
	}
}

function majListeObj(objVar,objListe) {
	nbvar = objVar.length;
	objListe.length = 0;
	j = 0;
	for(i=0;i<nbvar;i++) {
		elt = new Option(objVar[i].text, objVar[i].value, false, false);
		if(objVar[i].selected == true) {
			objListe[j] = elt;
			j++;
		}
	}
}

function selObj(objVar,value,selected) {
	nbvar = objVar.length;
	for(a=0;a<nbvar;a++) {
		if(objVar[a].value == value)
			objVar[a].selected = selected;
	}
}

function unsel(objVar,objListe) {
	nbobjsel = objListe.length;

	// Selection des sous groupe des familles selectionnees
	for(i=0; i<nbobjsel; i++) {
		if(objListe[i].selected == true) {
			// a-t-on selectionné une famille ?
			//alert("famille selectionnée : "+objListe[i].value);
				
			if (objListe[i].value.indexOf("FA") >= 0)
			{	//alert("famille selectionnée : "+objListe[i].value);
				for(j=i+1;j < nbobjliste;j++)
				{	//alert("sous-groupe selectionné : "+objListe[j].value+" ?");
					
					// est-ce un sous-groupe ?
					if (objListe[j] != null)
					if (objListe[j].value.indexOf("GP") >= 0)
					{	//alert("sous-groupe selectionné : "+objListe[j].text);
						// on sélectionne le sous-groupe
						objListe[j].selected = true;
						selObj(objVar, objListe[j].value, true);
					}
					else
					{	break;
					}
				}
			}

		}
	}

	// deselection des elements selectionnes a droite
	for(i=0; i<nbobjsel; i++) {
		if(objListe[i].selected == true) {
			selObj(objVar,objListe[i].value,false);
		}
	}
}

function invsel(objVar,objListe) {
	nbobjsel = objListe.length;
	for(i=0;i<nbobjsel;i++) {
		selObj(objVar,objListe[i].value,!objListe[i].selected);
	}
}

function addElt(select,text,value) {
	nb = select.length;
	select.length = nb+1;
	select[nb].value=value;
	select[nb].text=text;
}

function capturerDblClickSuppr() {
	remove();
}

function captureEnterSuppr(evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : 
		((evt.which) ? evt.which : 0));
	if(charCode == 13) remove();
}

function capturerDblClickAdd() {
	add();
}

function captureEnterAdd(evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : 
		((evt.which) ? evt.which : 0));
	if(charCode == 13) {
		add();
	}
}



var flag_top = false;
var myfolders;

function xGetElementById(e)
{
  if(typeof(e)!='string') return e;
  if(document.getElementById) e=document.getElementById(e);
  else if(document.all) e=document.all[e];
  else e=null;
  return e;
}

function xGetElementsByClassName(c,p,t,f)
{
  var found = new Array();
  var re = new RegExp('\\b'+c+'\\b', 'i');
  var list = xGetElementsByTagName(t, p);
  for (var i = 0; i < list.length; ++i) {
    if (list[i].className && list[i].className.search(re) != -1) {
      found[found.length] = list[i];
      if (f) f(list[i]);
    }
  }
  return found;
}

function xGetElementsByTagName(t,p)
{
  var list = null;
  t = t || '*';
  p = p || document;
  if (xIE4 || xIE5) {
    if (t == '*') list = p.all;
    else list = p.all.tags(t);
  }
  else if (p.getElementsByTagName) list = p.getElementsByTagName(t);
  return list || new Array();
}


function isTopRowClicked(id) {
  var clickedLabel = xGetElementById('label' + id);
  return (clickedLabel.parentNode.id == 'tabnav1');
}


function setupFolders() {
  myfolders = xGetElementsByClassName('folder', null, null);
  var myform = document.forms['carto_form']
  var folder_idx = myform.js_folder_idx.value;
  ontop(folder_idx);
}

function ontop(id) {
  for (i = 0; i < myfolders.length; i++) {
    currentFolder = myfolders[i];
    current = currentFolder.id.substring(6,7);
    currentLabel = xGetElementById('label' + current);
    if (current == id) {
      currentFolder.style.display = "block";
      currentLabel.className = 'active';
    } else {
      currentFolder.style.display = "none";
      currentLabel.className = '';
    }
  }
  document.carto_form.js_folder_idx.value = id;
  // temporary check to prevent safari from screwing the template
  // to remove once safari is patched
  // see http://bugzilla.opendarwin.org/show_bug.cgi?id=3677 for bug description
  // see http://developer.apple.com/internet/safari/uamatrix.html for safari version detection
  safari = false;
  xUA = navigator.userAgent.toLowerCase();
  i = xUA.indexOf('safari');
  if (i>0) {
      v = xUA.slice(i+7,xUA.length);
      if (v <= 312 || v == 412) {
          safari = true;
      }
  }

  if (!isTopRowClicked(id) && !safari)
    swapRows();

  if(id == 2){
    if(!flag_top) {
      flag_top = true;
      createMap();
    }
  }
}







