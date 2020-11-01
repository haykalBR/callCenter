import datatableConfig from "../Config/datatable";
import UsersController from "../Controller/UsersController";
import 'bootstrap-switch-button';
const $ = require('jquery');
export default class UsersEvent{
    private usersController:UsersController ;
    constructor(){
        this.usersController=new UsersController();
       var dataTable = $('#users_table').DataTable(this.setDatatableConfig());

        $('#searchByName').keyup(function(){
            dataTable.draw();
        });
        $('#searchByGender').change(function(){
            dataTable.draw();
        });
    }
    private setDatatableConfig(){

        datatableConfig.columnDefs = this.usersController.getDatableColumnDef();
        datatableConfig.ajax = this.usersController.getAjax();
        return datatableConfig;
    }




}