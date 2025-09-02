import{u as _,a as x,r as h,j as r}from"./app-Br-fQ3Mk.js";import{H as j}from"./Head-itrJw3Lk.js";import{N as b}from"./NavBar-BJZRD9WD.js";import{C as g,R as f,a as n,B as w}from"./Alert-COI26Jje.js";import{F as C,a as v}from"./Label-DRMq0b4Y.js";import{R as d,a as u}from"./RowFormGroupSelect-CQ0zfrXU.js";import"./Progress-DaekmABI.js";const D=({values:s,users:i})=>{const{data:a,setData:o,put:l,processing:m,errors:t}=_({name:s.name??"",user_id:s.user_id??"",two_gis_id:s.two_gis_id??"",pupr_user_id:s.pupr_user_id??""}),{routes:p}=x().props,[F,N]=h.useState(!1),c=e=>{e.preventDefault(),l(`/${p.backendprefix}/${p.brunch}/${s.id}`)};return r.jsxs(r.Fragment,{children:[r.jsx(j,{title:"Обновление филиала"}),r.jsx(b,{}),r.jsxs(g,{children:[r.jsx(f,{children:r.jsx(n,{children:r.jsx("h2",{children:"Обновление филиала"})})}),r.jsxs(C,{className:"row",onSubmit:c,children:[r.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[r.jsx(d,{label:"Наименование *",inputType:"text",value:a.name,onChange:e=>o("name",e.target.value),error:t.name}),r.jsx(u,{label:"Управляющий",options:i,value:a.user_id,onChange:e=>o("user_id",e.target.value),error:t.user_id}),r.jsx(u,{label:"Помошник управляющего",options:i,value:a.pupr_user_id,onChange:e=>o("pupr_user_id",e.target.value),error:t.pupr_user_id})]}),r.jsx(n,{className:`
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
