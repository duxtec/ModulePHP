class Usuários {
    constructor(tableselector){
        this.dataname = "usuários";
        this.tableselector = tableselector;
        this.urlselect = '/controller/select/usuarios';
        this.urlinsert = '/controller/insert/usuarios';
        this.urlupdate = '/controller/update/usuario';
        this.urldelete = '/controller/delete/usuario';

        this.exec();
    }

    async exec(){
        await this.loadDatas();
        this.renderTable();
    }

    async loadDatas(){ 
        this.tabledata
        this.tabledata = await fetch(this.urlselect)
        .then(res => res.json())
        .then(data => data);

        this.secundarydata = {
            "userlevels": {
                "urlselect": '/controller/select/userlevels',
                "select": {}
            }
        }

        Object.entries(this.secundarydata).forEach(async value => {
            this.secundarydata[value[0]].select = await fetch(this.secundarydata[value[0]].urlselect)
            .then(res => res.json())
            .then(data => data.select);
        })
    }
    renderTable() {
        if (this.tabledata["success"]) {
            this.table = new Tabulator(this.tableselector, {
                ajaxResponse:function(url, params, response){
                    return response.select;
                },
                rowFormatter:function(row){
                
                    var data = row.getData();
                
                    if(data.Habilitado == 1){
                        row.getElement().style.backgroundColor = "#8F8";
                    } else {
                        row.getElement().style.backgroundColor = "#F88";
                    }
                },
                data:this.tabledata.select, 
                columns: [
                    {
                        title:"ID", field:"ID", sorter:"number", download:true
                    },
                    {
                        title:"Nome", field:"Nome", visible:false, download:true
                    },
                    {
                        title:"Usuário", field:"Usuário", download:true
                    },
                    {
                        title:"E-mail", field:"E-mail", visible:false, download:true
                    },
                    {
                        title:"CPF", field:"CPF", visible:false, download:true
                    },
                    {
                        title:"Telefone", field:"Telefone", visible:false, download:true
                    },
                    {
                        title:"Nível de acesso", field:"Nível de acesso", download:true
                    },
                    {
                        title:"Habilitado", field:"Habilitado", formatter:"tickCross", download:true
                    },
                ],
                
                addRowPos:"top",
                history:true,  
                pagination:"local",
                paginationSize:10,
                paginationCounter:"rows",
                movableColumns:false,
                resizableRows:false,
                autoColumns:false,
                initialSort:[
                    {column:"ID", dir:"desc"},
                ],
                footerElement:`
                <div class="buttonsoptions">
                    <button onclick="usuario.add()" class="button button2">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button onclick="usuario.download()" class="button button2">
                        <i class="fa fa-download"></i>
                    </button>
                </div>
                `.trim(),
                locale:true,
                langs:{
                    "default":{
                        "pagination":{
                            "counter":{
                                "showing": "Mostrando",
                                "of": "de",
                                "rows": "linhas",
                                "pages": "páginas",
                            },
                            "first": "Primeira",
                            "prev": "Voltar",
                            "next": "Avançar",
                            "last": "última",
                        },
                    }
                },

            });
            let mainclass = this;
            
            this.table.on("rowClick", function(e, row){

                let data = row.getData();
                let htmlmodal = `<form id="update">`;
        
                Object.entries(data).forEach(value => {
                    if(["ID"].includes(value[0])) {
                        htmlmodal += `
                        <div class='container-label-input' style="display:none">
                            <label for='${value[0]}'>${value[0]}</label>
                            <input name='${value[0]}' type='hidden' value='${value[1]}' readonly/>
                        </div>
                        `;
                    } else if(["Nível de acesso"].includes(value[0])){
                       htmlmodal += `
                       <div class='container-label-input'>
                            <label for='${value[0]}'>${value[0]}</label>
                            <select name='${value[0]}' type='text' value='${value[1]}'>
                                <option value=''>${value[1]}</option>
                                ${mainclass.secundarydata.userlevels.select.map(userlevel => {
                                    return `<option value='${userlevel.ID}'>${userlevel.Nome}</option>`
                                })}
                            </select>
                        </div>
                       `;
                    } else if(["Habilitado"].includes(value[0])){
                       htmlmodal += `
                       <div class='container-label-input'>
                            <label for='${value[0]}'>${value[0]}</label>
                            <input name='${value[0]}' type='checkbox' ${value[1]==1?"checked": ""}/>
                        </div>
                       `;
                    } else {
                        htmlmodal += `
                        <div class='container-label-input'>
                            <label for='${value[0]}'>${value[0]}</label>
                            <input name='${value[0]}' type='text' value='${value[1]}'/>
                        </div>
                        `;
                    }
                });
                htmlmodal += `</form>`;
        
                // console.log(this)
                // var tableElement = this
                Swal.fire({
                    title: `<strong>Visualização de ${mainclass.dataname}</strong>`,
                    html: htmlmodal,
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText:
                    'Atualizar',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    cancelButtonText:
                    'Excluir',
                    cancelButtonAriaLabel: 'Thumbs down',
                }).then(async(result) => {
                    if (result.isConfirmed) {
        
                        var form = new FormData(document.querySelector("form#update"));
                        console.log(form);
        
                        let response = await fetch(mainclass.urlupdate, {
                            method: 'POST',
                            body: form
                        })
                        .then(res => res.json())
                        .then(data => data);
        
                        if (response.success) {
                            usuario.table.replaceData(mainclass.urlselect) 
                            Swal.fire(
                                'Sucesso!',
                                'Atualização efetuada com sucesso.',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Erro!',
                                response.status,
                                'error'
                            )
                        }
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        var form = new FormData(document.querySelector("form#update"));
                        console.log(form);
        
                        let response = await fetch(mainclass.urldelete, {
                            method: 'POST',
                            body: form
                        })
                        .then(res => res.json())
                        .then(data => data);
        
                        if (response.success) {
                            row.delete();
                            Swal.fire(
                                'Sucesso!',
                                'Exclusão efetuada com sucesso.',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Erro!',
                                response.status,
                                'error'
                            )
                        }
                    }
                })
            });
        } else {
            document.querySelector(this.tableselector).innerHTML = this.tabledata.status;
        }     
    }

    add(){
        let data = this.table.getColumnDefinitions();
        console.log(data);

        let htmlmodal = `<form id="insert">`;

        data.forEach(value => {
            value = value.field;
            if(["ID"].includes(value)) {

            } else if(["Nível de acesso"].includes(value)){
                htmlmodal += `
                <div class='container-label-input'>
                    <label for='${value}'>${value}</label>
                    <select name='${value}' type='text' value='2'>
                        ${this.secundarydata.userlevels.select.map(userlevel => {
                            return `<option value='${userlevel.ID}' ${userlevel.ID==2?"selected":""}>${userlevel.Nome}</option>`
                        })}
                    </select>
                </div>
                `;
            } else if(["Habilitado"].includes(value)){
                htmlmodal += `
                <div class='container-label-input'>
                     <label for='${value}'>${value}</label>
                     <input name='${value}' type='checkbox'/>
                 </div>
                `;
            } else {
                htmlmodal += `
                <div class='container-label-input'>
                    <label for='${value}'>${value}</label>
                    <input name='${value}' type='text' value=''/>
                </div>
                `;
            }
        });
        htmlmodal += `</form>`;

        let mainclass = this;

        Swal.fire({
            title: `<strong>Adicionar ${mainclass.dataname}</strong>`,
            html: htmlmodal,
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText:
            'Inserir',
            confirmButtonAriaLabel: 'Thumbs up, great!',
            cancelButtonText:
            'Cancelar',
            cancelButtonAriaLabel: 'Thumbs down',
        }).then(async(result) => {
            if (result.isConfirmed) {
                var form = new FormData(document.querySelector("form#insert"));
                console.log(form);

                let response = await fetch(mainclass.urlinsert, {
                    method: 'POST',
                    body: form
                })
                .then(res => res.json())
                .then(data => data);

                if (response.success) {
                    mainclass.table.replaceData(mainclass.urlselect) 
                    Swal.fire(
                        'Sucesso!',
                        'Inserção efetuada com sucesso.',
                        'success'
                    )
                } else {
                    Swal.fire(
                        'Erro!',
                        response.status,
                        'error'
                    )
                }
            }
        })
    }

    download(){
        this.table.download("xlsx", "usuario.xlsx", {sheetName:"Crachás"});
    }
}

var usuario = new Usuários("#usuarios");