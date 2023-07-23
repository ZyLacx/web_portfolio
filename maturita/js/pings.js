function Ping(text, alert = null){
    let divClass = "";
    if(alert !== null){
        divClass = "alert";
    }
    else{
        divClass = "notice";
    }
    let mainDiv = document.createElement('div');
    mainDiv.id = 'ping-main';
    $("body").append(mainDiv);
    if(Array.isArray(text)){
        text.forEach(element => {
            let div = document.createElement("div");
            div.className = divClass + " ping-div";
            let textNode = document.createTextNode(element);
            div.appendChild(textNode);
            $('#ping-main').append(div);
        });
    }
    else{
        let div = document.createElement("div");
        div.className = divClass + " ping-div";
        let textNode = document.createTextNode(text);
        div.appendChild(textNode);
        $('#ping-main').append(div);
    }
    setTimeout(function(){
        $('#ping-main').remove();
    }, 5500);
}

