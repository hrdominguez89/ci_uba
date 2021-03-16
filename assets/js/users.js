const enableDisableUsers = async (dataCheckbox)=>{
    const labelToggle = document.getElementsByClassName(dataCheckbox.id)[0];
    const userId = dataCheckbox.dataset.userId;
    const data = new FormData();
    data.append('user_id',userId);
    const init = {
        method: 'POST',
        body: data
    };
    if(dataCheckbox.checked){
        labelToggle.title="Desactivar usuario";
        const consulta = await fetch("enableUser", init);
    }else{
        labelToggle.title="Activar usuario";
        const consulta = await fetch("disableUser", init);
    }
}