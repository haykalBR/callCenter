import * as moment from "moment";
const routes = require('../../../public/js/fos_js_routes.json');
console.warn(routes);
import * as  Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from "../Config/axios";
import toastr from "../Config/toastr";
Routing.setRoutingData(routes);
export default class PermissonController{
    getAjax(){
        return {
            'url': "/admin/permission/",
            data: function(data) {

            },

        }
    }
    getDatableColumnDef(){
        let i =0;
        return [
            {   "targets": i++,'name':'t.id','data':'t_id'},
            {   "targets": i++,'name':'t.name','data':'t_name'},
            {   "targets": i++,'name':'t.guardName','data':'t_guardName' },
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
                    ch+= '<a data-toggle="tooltip" title="edit permission " href="'+Routing.generate('admin_edit_permission',{id:data})+'"><i class="fa fa-edit "></i></a> ';
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
     *  refresh new permission
     */
    refresh(){
        axios({
            method: 'GET',
            url: Routing.generate('admin_load_route'),
        }).then((data) => {
            var routes = Object.values(data);
            var list_route = $("#permission_guardName");
            list_route.html('');
            routes.forEach(function(route) {
                list_route.append('<option value="' + route + '">' + route+ '</option>');
            });
        }, (error) => {
            console.error(error);
        });
    }
    addNewPerlission(){
        axios({
            method: 'post',
            url: Routing.generate('admin_add_new_permission'),
        }).then((data) => {
            console.log(data);
        }, (error) => {
            console.error(error);
        });
    }

}