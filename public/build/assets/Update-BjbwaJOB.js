import{x,J as _,r as h,j as r}from"./app-D9eSIIOO.js";import{H as j}from"./Head-DV00g6x8.js";import{N as b}from"./NavBar-Lm7pVtg1.js";import{C as g,R as f,a as n,B as w}from"./Alert-DVQa3AGM.js";import{F as C,a as v}from"./Label-CouLC5El.js";import{R as d,a as l}from"./RowFormGroupSelect-mICrN6IS.js";import"./Progress-BA_Vauon.js";const D=({values:s,users:i})=>{const{data:a,setData:o,put:u,processing:m,errors:t}=x({name:s.name??"",user_id:s.user_id??"",two_gis_id:s.two_gis_id??"",pupr_user_id:s.pupr_user_id??""}),{routes:p}=_().props,[N,y]=h.useState(!1),c=e=>{e.preventDefault(),u(`/${p.backendprefix}/${p.brunch}/${s.id}`)};return r.jsxs(r.Fragment,{children:[r.jsx(j,{title:"Обновление филиала"}),r.jsx(b,{}),r.jsxs(g,{children:[r.jsx(f,{children:r.jsx(n,{children:r.jsx("h2",{children:"Обновление филиала"})})}),r.jsxs(C,{className:"row",onSubmit:c,children:[r.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[r.jsx(d,{label:"Наименование *",inputType:"text",value:a.name,onChange:e=>o("name",e.target.value),error:t.name}),r.jsx(l,{label:"Управляющий",options:i,value:a.user_id,onChange:e=>o("user_id",e.target.value),error:t.user_id}),r.jsx(l,{label:"Помошник управляющего",options:i,value:a.pupr_user_id,onChange:e=>o("pupr_user_id",e.target.value),error:t.pupr_user_id})]}),r.jsx(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:r.jsx(d,{label:"id 2Гис филиала",inputType:"text",value:a.two_gis_id,onChange:e=>o("two_gis_id",e.target.value),error:t.two_gis_id})}),r.jsx(n,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:r.jsx(v,{children:r.jsx(w,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:m,children:"Обновить"})})})]})]})]})};export{D as default};
