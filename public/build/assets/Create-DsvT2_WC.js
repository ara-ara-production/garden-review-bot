import{x as b,J as v,r as C,j as e}from"./app-zhzAyp58.js";import{H as y}from"./Head-ClWKlaNT.js";import{N as f}from"./NavBar--vOhkuN5.js";import{C as w,R as N,a as s,B as c}from"./Alert-Ck5wO5DS.js";import{F,a as x}from"./Label-BTOJADJT.js";import{R as t,a as _}from"./RowFormGroupSelect-BxmMqb-u.js";import"./Progress-DVecwT0F.js";import"./Input-D_eEr9ts.js";const D=({users:i})=>{var u,m;const{data:a,setData:n,post:g,processing:l,errors:o}=b({name:"",user_id:(u=i[0])==null?void 0:u.name,two_gis_id:"",yandex_vendor_id:"",google_map_id:"",pupr_user_id:(m=i[0])==null?void 0:m.name,address:""}),{routes:d}=v().props,[h,p]=C.useState(!1),j=r=>{r.preventDefault(),g(`/${d.backendprefix}/${d.brunch}`+(h?"?redirectOnCreation=true":""))};return e.jsxs(e.Fragment,{children:[e.jsx(y,{title:"Создание филиала"}),e.jsx(f,{}),e.jsxs(w,{children:[e.jsx(N,{children:e.jsx(s,{children:e.jsx("h2",{children:"Cоздание филиала"})})}),e.jsxs(F,{className:"row",onSubmit:j,children:[e.jsxs(s,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"Наименование *",inputType:"text",value:a.name,onChange:r=>n("name",r.target.value),error:o.name}),e.jsx(_,{label:"Управляющий",options:i,value:a.user_id,onChange:r=>n("user_id",r.target.value),error:o.user_id}),e.jsx(_,{label:"Помошник управляющего",options:i,value:a.pupr_user_id,onChange:r=>n("pupr_user_id",r.target.value),error:o.pupr_user_id})]}),e.jsxs(s,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"id 2Гис филиала",inputType:"text",value:a.two_gis_id,onChange:r=>n("two_gis_id",r.target.value),error:o.two_gis_id}),e.jsx(t,{label:"id Google филиала",inputType:"text",value:a.google_map_id,onChange:r=>n("google_map_id",r.target.value),error:o.google_map_id}),e.jsx(t,{label:"id Yandex.vendor филиала",inputType:"text",value:a.yandex_vendor_id,onChange:r=>n("yandex_vendor_id",r.target.value),error:o.yandex_vendor_id}),e.jsx(t,{label:"Адрес (используется для api)",inputType:"text",value:a.address,onChange:r=>n("address",r.target.value),error:o.address})]}),e.jsx(s,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(x,{children:e.jsx(c,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:l,onClick:()=>p(!1),children:"Сохранить"})})}),e.jsx(s,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(x,{children:e.jsx(c,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:l,onClick:()=>p(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{D as default};
