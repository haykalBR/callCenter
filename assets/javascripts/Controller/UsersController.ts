import * as moment from 'moment'
import 'bootstrap-switch-button';
const routes = require('../../../public/js/fos_js_routes.json');
import * as  Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);
import {randomString} from "../functions/Strings";
import axios from "../Config/axios";
import toastr from "../Config/toastr";
import 'select2';

export default class UsersController{
    getAjax(){
        return {
            'url': "/admin/users/",
            data: function(data) {

                data.join = [
                    {   "join": "App\\Domain\\Membre\\Entity\\Profile","alias": 'p',"condition": "t.id = p.user"}
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
            {   "targets": i++,'name':'t.roles','data':'t_roles'},
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
        });
    }
    grantPermission(current_val){
        var revokePermission =$('#user_revokePermission').select2('data').map(o => parseInt(o['id']));
        var n = this.inArray(current_val,revokePermission);
        //TODO TEst In array and remove from array
            revokePermission.remove(current_val);
            console.table(revokePermission);
            this.addRevokePermissionToSelect(revokePermission)
    }
    revokePermission(){

    }
     inArray(value, array) {
         array.forEach(function(number) {
            if (number==value) {
               return true
            }
         });
         return false;
    }
    private addPermissionToSelect(routes){
        this.addGrantPermissionToSelect(routes)
        this.addRevokePermissionToSelect(routes)
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