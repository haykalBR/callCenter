import moment from '../../Config/moment';
import Routing from '../../Config/routing';
import {randomString} from "../../Shared/helper/strings";
import axios from "../../Config/axios";
import Swal from "../../Config/sweetAlert";
import { injectable } from 'inversify';
import DataTable from '../../Shared/interfaces/datatable';
import {deleterecord, userpassword} from "../../Shared/helper/sweetalert2";

@injectable()
export default class UsersService implements DataTable{
    getAjax(){
        return {
            'url': Routing.generate('admin_users'),
            data: function(data) {
                data.join = [
                    {   "join": "App\\Domain\\Membre\\Entity\\Profile","alias": 'p',"condition": "t.id = p.user","type":""},
                ];
                data.hiddenColumn= [
                    {   name: 'p.mobile',data: 'p_mobile'}

                ];
                data.customSearch =[
                     {'name':'t.username','value':$('#search_users_username').val(),'type':'text'},
                     {'name':'t.email','value':$('#search_users_email').val(),'type':'text'},
                    {'name':'p.gender','value':$('#search_users_gender').val(),'type':'array'}
                  ]
                ;

            },

        }
    }
    getDatableColumnDef():Array<any>{
        let i =0;
        return [
            {   "targets": i++,'name':'t.id','data':'t_id'},
            {   "targets": i++,'name':'t.email','data':'t_email'},
            {   "targets": i++,'name':'p.firstName','data':'p_firstName' },
            {   "targets": i++,'name':'p.lastName','data':'p_lastName'},
            {   "targets": i++,'name':'p.id','data':'p_id',
                "render": function ( data, type, full, meta ) {
                return 44;
                }
            },
            {   "targets": i++,'name':'t.createdAt','data':'t_createdAt',
                "render": function ( data, type, full, meta ) {
                    return moment(new Date(data)).format();
                }
            },
            {
                "targets": -1,
                'name':'t.id',
                'data':'t_id',
                "render": function ( data, type, full, meta ) {
                   //     return data;
                    let ch="";
                     ch+= '<a data-toggle="tooltip" title="edit user " href="'+Routing.generate('admin_edit_users',{id:data})+'"><i class="fa fa-edit "></i></a> ';
                     ch+= '<a data-toggle="tooltip" title="remove user "  class="delete_user" data-user="'+full.t_email+'"  href="'+Routing.generate('admin_remove_users',{id:data})+'"><i class="fa fa-trash"></i></a> ';
                     ch+= '<a data-toggle="tooltip" title="regnreate password "  class="password_user" data-user="'+full.t_email+'" href="'+Routing.generate('admin_generate_password_users',{id:data})+'"><i class="fa fa-key"></i></a> ';
                     ch+= '<a data-toggle="tooltip" title=" voir Roles "  class="roles_user" data-user="'+full.t_id+'" data-email="'+full.t_email+'"><i class="fa fa-user"></i></a> ';
                     ch+='<input type="checkbox"  class="state_user" data-user="'+full.t_email+'"  data-toggle="switchbutton"  href="'+Routing.generate('admin_state_users',{id:data})+'" checked data-size="xs">';
                    return ch;
                }
            }

        ]
    }

    /**
     * genrete random password
     */
    randompaasword():void{
       let password =randomString(10,20);
       $('#user_plainPassword_first').val(password);
       $('#user_plainPassword_second').val(password);
    }

    reloadPermissions():void{
        let roles=$('#user_accessRoles').select2('data').map(o => parseInt(o['id']))
        axios({
            method: 'post',
            url: Routing.generate('admin_permissions_roles'),
            data: {
             roles: roles,
            }
        }).then((response) => {
            this.addPermissionToSelect( Object.values(response.data))
        }, (error) => {
            console.error(error)
        });
    }
    getRoles( event:JQuery.ClickEvent ):void{
        event.preventDefault();
        const user = {
            'id':event.target.parentElement.getAttribute("data-user"),
            'email':event.target.parentElement.getAttribute("data-email")
        }
        Swal.fire({
            title: "<span id='save-popup-title' style='text-overflow: ellipsis;max-width: 100%'>List Role "+user.email+"</span>",
            html: "<div id='save-popup-icon'></div>",
            confirmButtonColor: "#1a7bb9",
            confirmButtonText: "Ok",
            allowOutsideClick: false
        })
        let popupTextElement = document.getElementById("save-popup-icon");
        axios({
            method: 'get',
            url: Routing.generate('admin_user_roles',{id:user.id})
        }).then(async (response) => {
            var table =`<table class="table"><thead><tr><th scope="col">Role</th></tr></thead><tbody>`;
         await response.data.forEach(element =>{
                table+=`<tr><td >`+element.name+`</td></tr>`;
            });
            table+="</tbody> </table>"
            popupTextElement.innerHTML=table;
        }, (error) => {
            console.error(error)
        });

    }

    deleteUser(event:JQuery.ClickEvent):void{
        event.preventDefault();
        const email = $(this).attr('data-user');
        const url = event.target.parentElement.href;
        deleterecord(email,url);
    }

    passwordUser(event:JQuery.ClickEvent):void{
        event.preventDefault();
        const email = $(this).attr('data-user');
        const url = event.target.parentElement.href;
        userpassword(email,url);
    }

    private addPermissionToSelect(routes:Array<any>):void{
        this.addGrantPermissionToSelect(routes[0])
        this.addRevokePermissionToSelect(routes[1])
    }
    private addGrantPermissionToSelect(routes:Array<any>):void{
        var grantPermission = $("#user_grantPermission");
        grantPermission.html('');
        routes.forEach(function(route) {
            grantPermission.append('<option value="' + route.id + '">' + route.guardName+ '</option>');
        });
    }
    private addRevokePermissionToSelect(routes:Array<any>):void{
        var revokePermission = $("#user_revokePermission");
        revokePermission.html('');
        routes.forEach(function(route) {
            revokePermission.append('<option value="' + route.id + '">' + route.guardName+ '</option>');
        });
    }
}