import moment from 'moment';
import 'bootstrap-switch-button';
// const routes = require('../../../public/js/fos_js_routes.json');
import routes from '../../../public/js/fos_js_routes.json';
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);
import {randomString} from "../functions/Strings";
import axios from "../Config/axios";
import toastr from "../Config/toastr";
import 'select2';
import datatable from "../Config/datatable";
import Swal from "sweetalert2";

export default class UsersController{
    getAjax(){
        return {
            'url': "/admin/users/",
            data: function(data) {

                data.join = [
                    {   "join": "App\\Domain\\Membre\\Entity\\Profile","alias": 'p',"condition": "t.id = p.user","type":""},
                   // {   "join": "t.accessRoles","alias": 'r',"condition": " ","type":"many"}
                  //  {   "join": "App\\Domain\\Membre\\Entity\\UserPermission","alias": 'r',"condition": "t.id = r.user","type":""}
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
    getDatableColumnDef(){
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
                    return moment(new Date(data)).format("DD/MM/YYYY");
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
    getfnDrawCallback(){
        return function () {

          /*  $(".state_user").each((i,element:any) => {
                $(element).switchbutton({
                    onlabel: "Enabled f",
                    offlabel: "Disabled f"
                });
            });*/
        }
    }
    /**
     * genrete random password
     */
    randompaasword(){
       let password =randomString(10,20);
       $('#user_plainPassword_first').val(password);
       $('#user_plainPassword_second').val(password);
    }
    changeState(url,state){
        console.log('haikel');

    }
    reloadPermissions(roles){
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
    getRoles( user ){
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
    private addPermissionToSelect(routes){
        this.addGrantPermissionToSelect(routes[0])
        this.addRevokePermissionToSelect(routes[1])
    }
    private addGrantPermissionToSelect(routes){
        var grantPermission = $("#user_grantPermission");
        grantPermission.html('');
        routes.forEach(function(route) {
            grantPermission.append('<option value="' + route.id + '">' + route.guardName+ '</option>');
        });
    }
    private addRevokePermissionToSelect(routes){
        var revokePermission = $("#user_revokePermission");
        revokePermission.html('');
        routes.forEach(function(route) {
            revokePermission.append('<option value="' + route.id + '">' + route.guardName+ '</option>');
        });
    }
}