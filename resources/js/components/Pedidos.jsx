import React from "react"
import {distanciaFormatoStr, getEstado} from "../funciones"

window.COLUMNAS_TABLE = HEADS.filter(head=>head!=="#").map(head=>{
    let col = {displayName: head};
    /* 
            // ["Usuario", "Driver", "Origen", "Destino", "Pedido", "Plan", "Recorrido", "Estado"];
            <th scope="row">{{++$row}}</th>
            <td>{{isset($item->user) ? $item->user->phone : $item->user_id}}</td>
            <td>{{isset($item->driver) ? $item->driver->dni : ""}}</td>
            <td>{{$item->recojo->place->direccion}}</td>
            <td>{{$item->entrega->place->direccion}}</td>
            <td>{{$item->carga->tipo}}</td>
            <td>{{$item->plan->nombre}}</td>
            <td>{{$item->distanciaFormatoStr()}}</td>
            <td>{{$item->getEstado()}}</td>
    */
    switch (head) {
        case "Usuario":
            col.getDisplayValue = (row) => {
                return row.user ? (row.user.phone||"").replace("+51", "") : row.user_id;
            }
            break;
        case "Driver":
            col.getDisplayValue = (row) => {
                return row.driver_id ? (row.driver ? row.driver.dni : row.driver_id) : "";
            }
            break;
        case "Origen":
            col.getDisplayValue = (row) => {
                return row.recojo.place.direccion;
            }
            break;
        case "Destino":
            col.getDisplayValue = (row) => {
                return row.entrega.place.direccion;
            }
            break;
        case "Pedido":
            col.getDisplayValue = (row) => {
                return row.carga.tipo;
            }
            break;
        case "Plan":
            col.getDisplayValue = (row) => {
                return row.plan.nombre;
            }
            break;
        case "Recorrido":
            col.getDisplayValue = (row) => {
                return distanciaFormatoStr(row.distancia);
            }
            break;
        case "Estado":
            col.getDisplayValue = (row) => {
                return getEstado(row.estado);
            }
            break;
    }
    return col;
})