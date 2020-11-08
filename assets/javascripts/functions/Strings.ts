var randomize = require('randomatic');
/**
 * generate random strinf with pattern
 */
export function randomString(min,max) {

    return randomize('Aa0!', Math.floor(Math.random() * (max - min + 1)) + min);
}