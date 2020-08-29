const host = {
    url: "http://192.168.0.6"
}

export async function fetcher(url, options) {
    const {headers, ...rest} = options;
    console.log(url);
    const init = {
        method: "POST", headers: {
            'Content-Type': 'application/json',
            "Accept": "application/json",
            ...headers
        },
        ...rest
    };
    return await fetch(url, init).then(res=>{
        return res.json()
    }).then(val => {
        return val;
    })
}

export function getApiRoute(route) {
    return  host.url + "/api/" + route;
}

export async function fetcherApi(route, options) {
    return await fetcher(getApiRoute(route), options);
}

export async function fetcherApiAuth(route, options, user) {
    options.headers = {...options.headers, 
        "Authorization": "Bearer " + user.api_token
    };
    return await fetcherApi(route, options);
}

window.GetJsonEncodeData = function (encode) {
	var ta = document.createElement("textarea");
	ta.innerHTML = encode;
	const txt = ta.innerText.replace(/\\/g, "\\\\");
	window.PAGINATION_DATA = JSON.parse(txt);
}

export function getDisplayPhone(phone) {
    return ((phone||"")+"").replace("+51", "");
}

export function isFloat(n) {
	return Number(n) === n && Number(n) % 1 !== 0;
}

export function getDisplayPrecio(precio, defecto = "") {
    return !Number.isNaN(Number(precio)) && precio!==null && (precio+"").length ? "S/. " + (new Number(precio).toFixed(2).replace(".", ",")) : defecto;
}

export const getMedidas = ({alto, ancho, largo}={}) => {
	if (!alto && !ancho && !largo) return "";
	return (alto||"-") + " x " + (ancho||"-") + " x " + (largo||"-") + " (cm)";
}

export const isFragil = (fragil) => {
	if (typeof fragil == "undefined") return "";
	if (fragil) return "SI";
	return "NO";
}

export const getPeso = (peso) => {
	if (!peso) return "";
	return peso + " kg";
}

export function distanciaFormatoStr(distancia) {
    if (!distancia) return "";
    if (distancia<1000) {
        return (distancia + " m");
    } else {
        return ((distancia / 1000).toFixed(1) + " km");
    }
}

export function getEstado(estado) {
    if (!estado) {
        return "NO INICIADO";
    }
    return estado;
}
/* export async function axiosApi(route, options) {
    return 
} */
