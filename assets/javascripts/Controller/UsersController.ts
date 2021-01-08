import moment from 'moment';
import 'bootstrap-switch-button';
// const routes = require('../../../public/js/fos_js_routes.json');
import routes from '../../../public/js/fos_js_routes.json';
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);
export default class UsersController{

    getAjax(){
        return {
            'url': "/",
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
                    let ch="";
                     ch = '<a class="btn btn-info"  href="'+Routing.generate('admin_edit_users',{id:data})+'">Edit</a> ';
                     ch += '<a class="btn btn-info">Delete</a> ';
                     ch+='<input type="checkbox"  class="test" data-toggle="switchbutton" checked data-size="xs">';
                    return ch;
                }
            }

        ]
    }

}