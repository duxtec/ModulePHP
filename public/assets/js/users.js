class Users {
    constructor(tableselector) {
        this.dataname = "User";
        this.tableselector = tableselector;
        this.urlselect = '/controller/select/users';
        this.urlinsert = '/controller/insert/users';
        this.urlupdate = '/controller/update/users';
        this.urldelete = '/controller/delete/users';

        this.exec();
    }

    async exec() {
        await this.loadDatas();
        this.renderTable();
    }

    async loadDatas() {
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
                ajaxResponse: function (url, params, response) {
                    return response.select;
                },
                rowFormatter: function (row) {

                    var data = row.getData();

                    if (data.enabled == 1) {
                        row.getElement().style.backgroundColor = "#8F8";
                    } else {
                        row.getElement().style.backgroundColor = "#F88";
                    }
                },
                data: this.tabledata.select,
                columns: [
                    {
                        title: "ID", field: "ID", sorter: "number", download: true
                    },
                    {
                        title: "Name", field: "Name", visible: false, download: true
                    },
                    {
                        title: "Username", field: "Username", download: true
                    },
                    {
                        title: "E-mail", field: "E-mail", visible: false, download: true
                    },
                    {
                        title: "Userlevel", field: "Userlevel", download: true
                    },
                    {
                        title: "Enabled", field: "Enabled", formatter: "tickCross", download: true
                    },
                ],

                addRowPos: "top",
                history: true,
                pagination: "local",
                paginationSize: 10,
                paginationCounter: "rows",
                movableColumns: false,
                resizableRows: false,
                autoColumns: false,
                initialSort: [
                    { column: "ID", dir: "desc" },
                ],
                footerElement: `
                <div class="buttonsoptions">
                    <button onclick="user.add()" class="button button2">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button onclick="user.download()" class="button button2">
                        <i class="fa fa-download"></i>
                    </button>
                </div>
                `.trim(),
                locale: true,
                langs: {
                    "default": {
                        "pagination": {
                            "counter": {
                                "showing": "Showing",
                                "of": "of",
                                "rows": "rows",
                                "pages": "pages",
                            },
                            "first": "First",
                            "prev": "Prev",
                            "next": "Next",
                            "last": "Last",
                        },
                    }
                },

            });
            let mainclass = this;

            this.table.on("rowClick", function (e, row) {

                let data = row.getData();
                let htmlmodal = `<form id="update">`;

                Object.entries(data).forEach(value => {
                    if (["ID"].includes(value[0])) {
                        htmlmodal += `
                        <div class='container-label-input' style="display:none">
                            <label for='${value[0]}'>${value[0]}</label>
                            <input name='${value[0]}' type='hidden' value='${value[1]}' readonly/>
                        </div>
                        `;
                    } else if (["Nível de acesso"].includes(value[0])) {
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
                    } else if (["Enabled"].includes(value[0])) {
                        htmlmodal += `
                       <div class='container-label-input'>
                            <label for='${value[0]}'>${value[0]}</label>
                            <input name='${value[0]}' type='checkbox' ${value[1] == 1 ? "checked" : ""}/>
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
                    title: `<strong>${mainclass.dataname} View</strong>`,
                    html: htmlmodal,
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText:
                        'Save',
                    confirmButtonAriaLabel: 'Save',
                    cancelButtonText:
                        'Delete',
                    cancelButtonAriaLabel: 'Delete',
                }).then(async (result) => {
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
                                'Sucess!',
                                `${mainclass.dataname} saved successfully.`,
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Error!',
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
                                'Success!',
                                `${mainclass.dataname} deleted successfully.`,
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Error!',
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

    add() {
        let data = this.table.getColumnDefinitions();
        console.log(data);

        let htmlmodal = `<form id="insert">`;

        data.forEach(value => {
            value = value.field;
            if (["ID"].includes(value)) {

            } else if (["Userlevel"].includes(value)) {
                htmlmodal += `
                <div class='container-label-input'>
                    <label for='${value}'>${value}</label>
                    <select name='${value}' type='text' value='2'>
                        ${this.secundarydata.userlevels.select.map(userlevel => {
                    return `<option value='${userlevel.ID}' ${userlevel.ID == 2 ? "selected" : ""}>${userlevel.Nome}</option>`
                })}
                    </select>
                </div>
                `;
            } else if (["Enabled"].includes(value)) {
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
            title: `<strong>Add ${mainclass.dataname}</strong>`,
            html: htmlmodal,
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText:
                'Add',
            confirmButtonAriaLabel: 'Add',
            cancelButtonText:
                'Cancel',
            cancelButtonAriaLabel: 'Cancel',
        }).then(async (result) => {
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
                        'Sucess!',
                        `${mainclass.dataname} inserted successfully`,
                        'success'
                    )
                } else {
                    Swal.fire(
                        'Error!',
                        response.status,
                        'error'
                    )
                }
            }
        })
    }

    download() {
        this.table.download("xlsx", "users.xlsx", { sheetName: "Users" });
    }
}

var users = new users("#users");