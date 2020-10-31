import datatableConfig from "../Config/datatable";
import UsersController from "../Controller/UsersController";
import 'bootstrap-switch-button';
const $ = require('jquery');
export default class UsersEvent{
    private usersController:UsersController ;
    constructor(){
        this.usersController=new UsersController();
        $('#users_table').DataTable(this.setDatatableConfig());
        $('.test').bootstrapSwitch();
    }
    private setDatatableConfig(){

        datatableConfig.columnDefs = this.usersController.getDatableColumnDef();
        datatableConfig.ajax = this.usersController.getAjax();
        return datatableConfig;
    }
}