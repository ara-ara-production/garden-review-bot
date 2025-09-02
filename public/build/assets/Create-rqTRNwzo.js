import{u as g,a as C,r as v,j as e}from"./app-Br-fQ3Mk.js";import{H as f}from"./Head-itrJw3Lk.js";import{N as w}from"./NavBar-BJZRD9WD.js";import{C as N,R as y,a,B as d}from"./Alert-COI26Jje.js";import{F,a as c}from"./Label-DRMq0b4Y.js";import{R as x,a as h}from"./RowFormGroupSelect-CQ0zfrXU.js";import"./Progress-DaekmABI.js";const D=({users:s})=>{var m,p;const{data:n,setData:o,post:j,processing:i,errors:t}=g({name:"",user_id:(m=s[0])==null?void 0:m.name,two_gis_id:"",pupr_user_id:(p=s[0])==null?void 0:p.name}),{routes:l}=C().props,[b,u]=v.useState(!1),_=r=>{r.preventDefault(),j(`/${l.backendprefix}/${l.brunch}`+(b?"?redirectOnCreation=true":""))};return e.jsxs(e.Fragment,{children:[e.jsx(f,{title:"Создание филиала"}),e.jsx(w,{}),e.jsxs(N,{children:[e.jsx(y,{children:e.jsx(a,{children:e.jsx("h2",{children:"Cоздание филиала"})})}),e.jsxs(F,{className:"row",onSubmit:_,children:[e.jsxs(a,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(x,{label:"Наименование *",inputType:"text",value:n.name,onChange:r=>o("name",r.target.value),error:t.name}),e.jsx(h,{label:"Управляющий",options:s,value:n.user_id,onChange:r=>o("user_id",r.target.value),error:t.user_id}),e.jsx(h,{label:"Помошник управляющего",options:s,value:n.pupr_user_id,onChange:r=>o("pupr_user_id",r.target.value),error:t.pupr_user_id})]}),e.jsx(a,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:e.jsx(x,{label:"id 2Гис филиала",inputType:"text",value:n.two_gis_id,onChange:r=>o("two_gis_id",r.target.value),error:t.two_gis_id})}),e.jsx(a,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(c,{children:e.jsx(d,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:i,onClick:()=>u(!1),children:"Сохранить"})})}),e.jsx(a,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(c,{children:e.jsx(d,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:i,onClick:()=>u(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{D as default};
