import moment from '../../Config/moment';
import axios from '../../Config/axios';
import Routing from '../../Config/routing';
import Datatable from "../../Shared/interfaces/datatable";
import { injectable } from 'inversify';

@injectable()
export default class PermissionService implements Datatable{
    
    constructor(){}

    getAjax(){
        return {
            'url': "/admin/permission/",
            data: function(data) {

            },

        }
    }
    getDatableColumnDef():Array<any>{
        let i =0;
        return [
            {   "targets": i++,'name':'t.id','data':'t_id'},
            {   "targets": i++,'name':'t.name','data':'t_name'},
            {   "targets": i++,'name':'t.guardName','data':'t_guardName' },
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
                    let ch="";
                    ch+= '<a data-toggle="tooltip" title="edit permission " href="'+Routing.generate('admin_edit_permission',{id:data})+'"><i class="fa fa-edit "></i></a> ';
                    return ch;
                }
            }

        ]
    }

    /**
     *  refresh new permission
     */
    refresh():void{
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
    addNewPerlission():void{
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