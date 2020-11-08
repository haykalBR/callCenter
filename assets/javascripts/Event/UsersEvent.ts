import datatableConfig from "../Config/datatable";
import UsersController from "../Controller/UsersController";
import 'bootstrap-switch-button';
import Swal from 'sweetalert2'

const $ = require('jquery');
export default class UsersEvent{
    private usersController:UsersController ;
    constructor(){
        this.usersController= new UsersController();
        var dataTable = $('#users_table').DataTable(this.setDatatableConfig());
        this.Search(dataTable);
        $('')
    }
    private setDatatableConfig(){

        datatableConfig.columnDefs = this.usersController.getDatableColumnDef();
        datatableConfig.ajax = this.usersController.getAjax();
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

}