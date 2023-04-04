import CryptoJS from 'crypto-js';

const cryptoOptions = {
  mode: CryptoJS.mode.CFB,
  padding: CryptoJS.pad.NoPadding,
};

function dec2hex (dec) {
  return dec.toString(16).padStart(2, '0')
}

export function generateKey() {
  const input = new Uint8Array(24)
  window.crypto.getRandomValues(input)

  return Array.from(input, dec2hex).join('')
}

export function encrypt(value, key) {
  return CryptoJS.AES.encrypt(value, key, cryptoOptions).toString();
}

export function decrypt(value, key) {
  return CryptoJS.AES.decrypt(value, key, cryptoOptions).toString(CryptoJS.enc.Utf8);
}
