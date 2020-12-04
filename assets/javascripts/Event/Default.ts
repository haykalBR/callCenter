import 'bootstrap';
import 'bootstrap-switch-button';
import dropifyConfig from '../Config/dropify';
import datepickerConfig from '../Config/datepicker';
import datatableConfig from '../Config/datatable';
import DefaultController from '../Controller/DefaultController';
import DefaultComponent from '../Component/DefaultComponent';
 
export default class Default{
    private defaultControler:DefaultController;
    constructor(){
        this.defaultControler=new DefaultController();
        $('#example').DataTable(this.setDatatableConfig());
        $('.js-datepicker').datepicker(datepickerConfig);
        $('.dropify-fr').dropify(dropifyConfig);
        // ReactDOM.render(<DefaultComponent />, document.querySelector('#react-test'));
    }

    private setDatatableConfig(){
        datatableConfig.columnDefs = this.defaultControler.getDatableColumnDef();
        datatableConfig.ajax = this.defaultControler.getAjax();
        return datatableConfig;
    }
    
}
   
