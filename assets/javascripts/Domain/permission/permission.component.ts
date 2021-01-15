import { inject, injectable } from "inversify";
import DatatableFactory from "../../Shared/factory/datatableFactory";
import PermissionService from "./permission.service";

@injectable()
export default class PermissionComponent{
    private permissionService:PermissionService;

    constructor(
        @inject(PermissionService) permissionService:PermissionService,
        @inject(DatatableFactory) datatableFactory:DatatableFactory
    ){
        this.permissionService = permissionService;
        datatableFactory.getDatatable('#permission_table', permissionService)
    }


    /**
     *  refresh list of route gurad
     */
    refresh():void{
        $('#permission_refresh').on('click',
            this.permissionService.refresh
        );
    }
    addNewPerlission():void{
        $('#add_new_permission').on('click',
            this.permissionService.addNewPerlission
        );

    }
}