import{x as v,J as b,r as y,j as e}from"./app-OylCr9Tv.js";import{H as C}from"./Head-BCdnzafk.js";import{N as f}from"./NavBar-DCWNL8uJ.js";import{C as w,R as N,a as s,B as x}from"./Alert-DJwVXH-o.js";import{F,a as _}from"./Label-eOr0Zt6B.js";import{R as t,a as c}from"./RowFormGroupSelect-C7eM6E1d.js";import"./Progress-DfervQBR.js";import"./FormFeedback-EtjYHvFE.js";import"./Input-ByNWNJhS.js";const E=({users:i})=>{var u,m;const{data:a,setData:n,post:g,processing:d,errors:o}=v({name:"",user_id:(u=i[0])==null?void 0:u.name,two_gis_id:"",yandex_vendor_id:"",yandex_map_id:"",google_map_id:"",pupr_user_id:(m=i[0])==null?void 0:m.name,address:""}),{routes:l}=b().props,[h,p]=y.useState(!1),j=r=>{r.preventDefault(),g(`/${l.backendprefix}/${l.brunch}`+(h?"?redirectOnCreation=true":""))};return e.jsxs(e.Fragment,{children:[e.jsx(C,{title:"Создание филиала"}),e.jsx(f,{}),e.jsxs(w,{children:[e.jsx(N,{children:e.jsx(s,{children:e.jsx("h2",{children:"Cоздание филиала"})})}),e.jsxs(F,{className:"row",onSubmit:j,children:[e.jsxs(s,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"Наименование *",inputType:"text",value:a.name,onChange:r=>n("name",r.target.value),error:o.name}),e.jsx(c,{label:"Управляющий",options:i,value:a.user_id,onChange:r=>n("user_id",r.target.value),error:o.user_id}),e.jsx(c,{label:"Помошник управляющего",options:i,value:a.pupr_user_id,onChange:r=>n("pupr_user_id",r.target.value),error:o.pupr_user_id})]}),e.jsxs(s,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"id 2Гис филиала",inputType:"text",value:a.two_gis_id,onChange:r=>n("two_gis_id",r.target.value),error:o.two_gis_id}),e.jsx(t,{label:"id Google филиала",inputType:"text",value:a.google_map_id,onChange:r=>n("google_map_id",r.target.value),error:o.google_map_id}),e.jsx(t,{label:"id Yandex.vendor филиала",inputType:"text",value:a.yandex_vendor_id,onChange:r=>n("yandex_vendor_id",r.target.value),error:o.yandex_vendor_id}),e.jsx(t,{label:"id Yandex.Карты филиала",inputType:"text",value:a.yandex_map_id,onChange:r=>n("yandex_map_id",r.target.value),error:o.yandex_map_id}),e.jsx(t,{label:"Адрес (используется для api)",inputType:"text",value:a.address,onChange:r=>n("address",r.target.value),error:o.address})]}),e.jsx(s,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(_,{children:e.jsx(x,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:d,onClick:()=>p(!1),children:"Сохранить"})})}),e.jsx(s,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(_,{children:e.jsx(x,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:d,onClick:()=>p(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{E as default};
