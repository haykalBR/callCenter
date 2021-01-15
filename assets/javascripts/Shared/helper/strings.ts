import randomize from 'randomatic';

/**
 * generate random strinf with pattern
 */
export function randomString(min:number,max:number):string {

    return randomize('Aa0!', Math.floor(Math.random() * (max - min + 1)) + min);
}