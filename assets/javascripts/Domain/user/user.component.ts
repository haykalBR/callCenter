import {deleterecord, userpassword} from "../../Shared/helper/sweetalert2";
import { inject, injectable } from "inversify";
import UsersService from "./user.service";
import DatatableFactory from "../../Shared/factory/datatableFactory";

@injectable()
export default class UserComponent{
    private usersService:UsersService ;

    constructor(
        @inject(UsersService) usersService:UsersService,
        @inject(DatatableFactory) datatableFactory:DatatableFactory,
    ){
        this.usersService= usersService;
        let dataTable = datatableFactory.getDatatable('#users_table', usersService);
        this.Search(dataTable);
    }
    
    private  Search(dataTable):void{
        $('#search_users_username').on("keyup",dataTable.draw);
        $('#search_users_email').on("keyup",dataTable.draw);
        $('#search_users_gender').on("keyup",dataTable.draw);
    }

    generatePassword():void{
        $('#user_random_password').on('click',
            this.usersService.randompaasword
        );
    }

    deleteUser():void{
        $("#users_table").on('click', '.delete_user', 
            this.usersService.deleteUser
        );
    }

    passwordUser():void{
        $("#users_table").on('click', '.password_user', 
            this.usersService.passwordUser
        );
    }

    reloadPermissions():void{
        $('#user_accessRoles').on('change',
            this.usersService.reloadPermissions
        );
    }

    getRoles():void{
        $("#users_table").on('click', '.roles_user', 
            this.usersService.getRoles
        );
    }

}