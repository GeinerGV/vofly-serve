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
    return (phone||"").replace("+51", "");
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
