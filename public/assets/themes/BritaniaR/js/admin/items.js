window.onload = () => {
    let items = document.querySelectorAll('.item_id');
    
    if (items != null) {
        items.forEach(item => {
            item.addEventListener('click', (e) => {
                console.log(e.target)
                let id = e.target.innerText
                let tempInput = document.createElement('INPUT');
                let body = document.getElementsByTagName('body')[0]
                body.appendChild(tempInput);
                tempInput.setAttribute('value', id)
                tempInput.select();
                document.execCommand('copy');
                body.removeChild(tempInput);
            })
        })
    }   
}
