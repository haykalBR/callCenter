import datatableConfig from "../Config/datatable";
import UsersController from "../Controller/UsersController";
import 'bootstrap-switch-button';
import {deleterecord, userpassword} from "../functions/sweetalert2";

const $ = require('jquery');
import 'select2';
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

        $("#users_table").on('click', '.password_user', function () {
            event.preventDefault();
            const email = $(this).attr('data-user');
            const url = this.href;
            userpassword(email,url);
        });
    }
    changeStateUser(){
        $("#users_table").on('change', '.state_user', function (event) {
            event.preventDefault();
         /*   const email = $(this).attr('data-user');
            const url = this.href;
            userpassword(email,url);*/
        });
    }
    reloadPermissions(){
        $('#user_accessRoles').on('change',()=>{
            var Selection =$('#user_accessRoles').select2('data').map(o => parseInt(o['id']));
            this.usersController.reloadPermissions(Selection);
        });
    }
    permissionsGrantAndRevoke(){

    }
    getRoles(){
        $("#users_table").on('click', '.roles_user',  (event)=> {
            event.preventDefault();
            const user = {
                'id':event.target.parentElement.getAttribute("data-user"),
                'email':event.target.parentElement.getAttribute("data-email")
            }
            this.usersController.getRoles(user);
        });
    }

}