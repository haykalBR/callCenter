import { inject, injectable } from "inversify";
import dropifyConfig from '../../../Config/dropify';
import datepickerConfig from '../../../Config/datepicker';
import 'select2';
import 'bootstrap-switch-button';
import MainService from "./main.service";

@injectable()
export default class MainComponent{
    constructor(
        @inject(MainService) mainService:MainService
    ){
        $('.js-datepicker').datepicker(datepickerConfig);
        $('.dropify-fr').dropify(dropifyConfig);
        $('.select2').select2(dropifyConfig);
    }
}