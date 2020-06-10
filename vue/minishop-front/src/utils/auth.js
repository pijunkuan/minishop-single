/*
	localStorage存储
 */
import Cookies from 'js-cookie'

const TokenKey = 'MiniToken'

export function getToken () {
  return Cookies.get(TokenKey)
}
export function setToken (token) {
  return Cookies.set(TokenKey, token)
}

export function removeToken () {
  return Cookies.remove(TokenKey)
}


export function getExtime () {
  return Cookies.get('expire_time')
}
export function setExtime (time) {
  return Cookies.set('expire_time', time)
}

export function removeExtime () {
  return Cookies.remove('expire_time')
}


export function getTheme(){
	return Cookies.get('themeName')
}

export function setTheme(theme){
	return Cookies.set('themeName',theme)
}

export function removeTheme(){
	return Cookies.remove('themeName')
}