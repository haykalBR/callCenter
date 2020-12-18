import datatableConfig from "../Config/datatable";
import 'bootstrap-switch-button';
import {deleterecord, userpassword} from "../functions/sweetalert2";
import PermissonController from "../Controller/PermissonController";
import {refresh} from "ionicons/icons";
const $ = require('jquery');
export default class PermissonEvent{
    private permissonController:PermissonController;

    constructor(){
        console.warn(544554)
        this.permissonController= new PermissonController();
        var dataTable = $('#permission_table').DataTable(this.setDatatableConfig());
    }
    private setDatatableConfig(){
        datatableConfig.columnDefs = this.permissonController.getDatableColumnDef();
        datatableConfig.ajax = this.permissonController.getAjax();
        datatableConfig.fnDrawCallback=this.permissonController.getfnDrawCallback();
        return datatableConfig;
    }

    /**
     *  refresh list of route gurad
     */
    refresh(){
        $('#permission_refresh').on('click',()=>{
            this.permissonController.refresh();
        });
    }



}