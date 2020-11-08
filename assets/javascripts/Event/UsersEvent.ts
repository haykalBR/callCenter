import datatableConfig from "../Config/datatable";
import UsersController from "../Controller/UsersController";
import 'bootstrap-switch-button';
import {deleterecord, userpassword} from "../functions/sweetalert2";

const $ = require('jquery');
export default class UsersEvent{
    private usersController:UsersController ;

    constructor(){
        this.usersController= new UsersController();
        var dataTable = $('#users_table').DataTable(this.setDatatableConfig());
        this.Search(dataTable);
    }
    private setDatatableConfig(){
        datatableConfig.columnDefs = this.usersController.getDatableColumnDef();
        datatableConfig.ajax = this.usersController.getAjax();
        datatableConfig.fnDrawCallback=this.usersController.getfnDrawCallback();
        return datatableConfig;
    }
    private  Search(dataTable){
        $('#search_users_username').keyup(function(){
            dataTable.draw();
        });
        $('#search_users_email').keyup(function(){
            dataTable.draw();
        });
        $('#search_users_gender').change(function(){
            dataTable.draw();
        });
    }

    generatePassword(){
        $('#user_random_password').on('click',()=>{
                this.usersController.randompaasword();
        });
    }
    deleteUser(){
        $("#users_table").on('click', '.delete_user', function (event) {
            event.preventDefault();
            const email = $(this).attr('data-user');
            const url = this.href;
            deleterecord(email,url);

        });
    }
    passwordUser(){
        $("#users_table").on('click', '.password_user', function (event) {
            event.preventDefault();
            const email = $(this).attr('data-user');
            const url = this.href;
            userpassword(email,url);
        });
    }
    changeStateUser(){
        $("#users_table").on('change', '.state_user', function (event) {
            event.preventDefault();
            console.log(159258)
         /*   const email = $(this).attr('data-user');
            const url = this.href;
            userpassword(email,url);*/
        });
    }

}