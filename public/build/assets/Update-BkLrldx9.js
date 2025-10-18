import{x,J as g,r as c,j as e}from"./app-CLCP7UJ0.js";import{H as h}from"./Head-ChUJSszd.js";import{N as j}from"./NavBar-CkTtJJc8.js";import{C as b,R as v,a as d,B as y}from"./Alert-CpvvnA6G.js";import{F as C,a as f}from"./Label-ClGKVjrm.js";import{R as t,a as p}from"./RowFormGroupSelect-CX2GGkyV.js";import"./Progress-CDnMGeRI.js";import"./Input-7JvBkm-L.js";const D=({values:a,users:i})=>{const{data:o,setData:n,put:u,processing:_,errors:s}=x({name:a.name??"",user_id:a.user_id??"",two_gis_id:a.two_gis_id??"",yandex_vendor_id:a.yandex_vendor_id??"",google_map_id:a.google_map_id??"",pupr_user_id:a.pupr_user_id??"",address:a.address??""}),{routes:l}=g().props,[w,N]=c.useState(!1),m=r=>{r.preventDefault(),u(`/${l.backendprefix}/${l.brunch}/${a.id}`)};return e.jsxs(e.Fragment,{children:[e.jsx(h,{title:"Обновление филиала"}),e.jsx(j,{}),e.jsxs(b,{children:[e.jsx(v,{children:e.jsx(d,{children:e.jsx("h2",{children:"Обновление филиала"})})}),e.jsxs(C,{className:"row",onSubmit:m,children:[e.jsxs(d,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"Наименование *",inputType:"text",value:o.name,onChange:r=>n("name",r.target.value),error:s.name}),e.jsx(p,{label:"Управляющий",options:i,value:o.user_id,onChange:r=>n("user_id",r.target.value),error:s.user_id}),e.jsx(p,{label:"Помошник управляющего",options:i,value:o.pupr_user_id,onChange:r=>n("pupr_user_id",r.target.value),error:s.pupr_user_id})]}),e.jsxs(d,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"id 2Гис филиала",inputType:"text",value:o.two_gis_id,onChange:r=>n("two_gis_id",r.target.value),error:s.two_gis_id}),e.jsx(t,{label:"id Google филиала",inputType:"text",value:o.google_map_id,onChange:r=>n("google_map_id",r.target.value),error:s.google_map_id}),e.jsx(t,{label:"id Yandex.vendor филиала",inputType:"text",value:o.yandex_vendor_id,onChange:r=>n("yandex_vendor_id",r.target.value),error:s.yandex_vendor_id}),e.jsx(t,{label:"Адрес (используется для api)",inputType:"text",value:o.address,onChange:r=>n("address",r.target.value),error:s.address})]}),e.jsx(d,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(f,{children:e.jsx(y,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:_,children:"Обновить"})})})]})]})]})};export{D as default};
