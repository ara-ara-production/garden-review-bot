import{x,J as _,r as h,j as r}from"./app-BcHd4eWn.js";import{H as j}from"./Head-_xtiLubh.js";import{N as b}from"./NavBar-jo5iGGA3.js";import{C as g,R as f,a as n,B as w}from"./Alert-CWZpn_r8.js";import{F as C,a as v}from"./Label-sRUBmtYk.js";import{R as i}from"./RowFormGroup-BZ4921Nw.js";import{R as l}from"./RowFormGroupSelect-D7y3-bLI.js";import"./Progress-CUABgi0U.js";const D=({values:s,users:d})=>{const{data:a,setData:t,put:u,processing:m,errors:o}=x({name:s.name??"",user_id:s.user_id??"",two_gis_id:s.two_gis_id??"",pupr_user_id:s.pupr_user_id??"",address:s.address??""}),{routes:p}=_().props,[y,N]=h.useState(!1),c=e=>{e.preventDefault(),u(`/${p.backendprefix}/${p.brunch}/${s.id}`)};return r.jsxs(r.Fragment,{children:[r.jsx(j,{title:"Обновление филиала"}),r.jsx(b,{}),r.jsxs(g,{children:[r.jsx(f,{children:r.jsx(n,{children:r.jsx("h2",{children:"Обновление филиала"})})}),r.jsxs(C,{className:"row",onSubmit:c,children:[r.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[r.jsx(i,{label:"Наименование *",inputType:"text",value:a.name,onChange:e=>t("name",e.target.value),error:o.name}),r.jsx(l,{label:"Управляющий",options:d,value:a.user_id,onChange:e=>t("user_id",e.target.value),error:o.user_id}),r.jsx(l,{label:"Помошник управляющего",options:d,value:a.pupr_user_id,onChange:e=>t("pupr_user_id",e.target.value),error:o.pupr_user_id})]}),r.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[r.jsx(i,{label:"id 2Гис филиала",inputType:"text",value:a.two_gis_id,onChange:e=>t("two_gis_id",e.target.value),error:o.two_gis_id}),r.jsx(i,{label:"Адрес (используется для api)",inputType:"text",value:a.address,onChange:e=>t("address",e.target.value),error:o.address})]}),r.jsx(n,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:r.jsx(v,{children:r.jsx(w,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:m,children:"Обновить"})})})]})]})]})};export{D as default};
