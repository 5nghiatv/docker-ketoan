
// Reference messages collection

// Listen for form submit
if(document.getElementById('contactForm')){
  document.getElementById('contactForm').addEventListener('submit', submitForm);

  var messagesRef = firebase.database().ref('messages');

  // Test
  var firetore = firebase.firestore();
  var data = [] ; // get dữ liệu vào data
  readFirestore(firetore,'messages')
  //readFirestore(firetore,'cities')
  console.log(data);

    // firebase.database().ref('messages/-M6SIIPAC6pOsQybH5pz').once('value').then(function(snapshot) {
  //   var message = (snapshot.val() && snapshot.val().message) || 'Không tìm thấy !!';
  //   console.log('message: '+message)
  // });

}  

if(document.getElementById('listData')){
  document.getElementById('listData').addEventListener('click', submitList);

}

//======================================
//======================================
function submitList(e){
  e.preventDefault();

  if($('#listData')[0].value){
    //alert($('#listData')[0].value) ;
    $('#data_name').val($('#listData')[0].value)
    //console.log($('#data_name').val() ) ;
  }

  if($('#listData')[0]['childElementCount'] > 1 ) { return ;}

  if(window.XMLHttpRequest){ XMLHttpRequestObject=new XMLHttpRequest(); }
  else if(window.ActiveXObject) { XMLHttpRequestObject=new ActiveXObject("Microsoft.XMLHTTP");} 

  XMLHttpRequestObject.onreadystatechange=function(){
    if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
    {
      var dat = XMLHttpRequestObject.responseText ;
      dat = jQuery.parseJSON(dat);
      $.each(dat, function(i, item) {
         var html = `<option value="`+item +`" >`+ item +`</option>`
         // console.log(html)
         $('#listData').append(`<option value="${item}"> ${item} </option>`); 

      });
    }
  }
    
  var username = getInputVal('username');
  var password = getInputVal('pwd');
  XMLHttpRequestObject.open("GET","upload.php?username="+username+"&password="+password,true);
  XMLHttpRequestObject.send();
}
//======================================
// Submit form
function submitForm(e){
  e.preventDefault();

  // Get values
  var name = getInputVal('name');
  var company = getInputVal('company');
  var email = getInputVal('email');
  var phone = getInputVal('phone');
  var message = getInputVal('message');
  var address = getInputVal('address');
  var date = new Date().toISOString().replace(/T/, ' ').replace(/\..+/, '')
 
  // Save message
  saveMessage(name, company, email, phone, message, address, date);
  // Check at :  https://console.firebase.google.com/u/1/project/ketoan-acn1/database/firestore/data~2Fmessages
  // Show alert
  document.querySelector('.alert').style.display = 'block';

  // Hide alert after 3 seconds
  setTimeout(function(){
    document.querySelector('.alert').style.display = 'none';
  },3000);

  // Clear form
  document.getElementById('contactForm').reset();
}
//=====================
// Function to get get form values
function getInputVal(id){
  return document.getElementById(id).value;
}

//=====================  Save message to firebase
function saveMessage(name, company, email, phone, message, address, date){
  var data = {
    name: name,
    company:company,
    email:email,
    phone:phone,
    message:message,
    address:address,
    date:date,
  }
  //===== realtime-Database
  var newMessageRef = messagesRef.push();
  var test = newMessageRef.set(data );
  console.log(test);
  //==== Cloud-Firestore
  //var test = firetore.collection("messages").add(data) ;
  var test = firetore.collection("messages").doc().set(data) ;
  console.log(test);
}
//=======================================
function  readFirestore(firetore,obj)  {
  firetore.collection(obj).get()
    .then(function(querySnapshot ) {
      querySnapshot.forEach(function(doc ) {
        // doc.data() is never undefined for query doc snapshots
        // console.log(doc.id, " => ", doc.data());
        var tmp = doc.data() ;  // Lấy Data
        tmp['id'] = doc.id ;    // Thêm ID
        // tmp.sort(fn($a, $b) => strcmp($a->date, $b->date));
        data.push(tmp);  // push vào data is var public
    });
  });

}        
//=======================================
function  readcities(firetore)  {
  firetore.collection("cities").doc("SF")
    .get()
    .then(function(doc) {
      if (doc.exists) {
        console.log("Document data:", doc.data());
      } else {
        // doc.data() will be undefined in this case
        console.log("No such document! - add ");
        var citiesRef = firetore.collection("cities");
            citiesRef.doc("SF").set({
                name: "San Francisco", state: "CA", country: "USA",
                capital: false, population: 860000,
                regions: ["west_coast", "norcal"] });

              }
    }).catch(function(error) {
      console.log("Error getting document:", error);
    });

}
