function makeItVisible(){
var personId = document.getElementById("ohId");
if (personId.value == "ine") {
        document.getElementById("ohInputs").style.display = 'block';
    } else {
        document.getElementById("ohInputs").style.display = 'none';
    }
}