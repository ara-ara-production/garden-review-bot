import{x as g,J as v,r as C,j as e}from"./app-BmDeQ7V-.js";import{H as f}from"./Head-sasVaX65.js";import{N as w}from"./NavBar-ByTnacCq.js";import{C as y,R as N,a as t,B as c}from"./Alert-BMFFu7r-.js";import{F,a as x}from"./Label-CmvS764P.js";import{R as i,a as h}from"./RowFormGroupSelect-NUXRCvBF.js";import"./Progress-BRY5KVQQ.js";import"./Input-D1D38yo0.js";const D=({users:o})=>{var u,m;const{data:a,setData:s,post:j,processing:l,errors:n}=g({name:"",user_id:(u=o[0])==null?void 0:u.name,two_gis_id:"",pupr_user_id:(m=o[0])==null?void 0:m.name,address:""}),{routes:d}=v().props,[b,p]=C.useState(!1),_=r=>{r.preventDefault(),j(`/${d.backendprefix}/${d.brunch}`+(b?"?redirectOnCreation=true":""))};return e.jsxs(e.Fragment,{children:[e.jsx(f,{title:"Создание филиала"}),e.jsx(w,{}),e.jsxs(y,{children:[e.jsx(N,{children:e.jsx(t,{children:e.jsx("h2",{children:"Cоздание филиала"})})}),e.jsxs(F,{className:"row",onSubmit:_,children:[e.jsxs(t,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(i,{label:"Наименование *",inputType:"text",value:a.name,onChange:r=>s("name",r.target.value),error:n.name}),e.jsx(h,{label:"Управляющий",options:o,value:a.user_id,onChange:r=>s("user_id",r.target.value),error:n.user_id}),e.jsx(h,{label:"Помошник управляющего",options:o,value:a.pupr_user_id,onChange:r=>s("pupr_user_id",r.target.value),error:n.pupr_user_id})]}),e.jsxs(t,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(i,{label:"id 2Гис филиала",inputType:"text",value:a.two_gis_id,onChange:r=>s("two_gis_id",r.target.value),error:n.two_gis_id}),e.jsx(i,{label:"Адрес (используется для api)",inputType:"text",value:a.address,onChange:r=>s("address",r.target.value),error:n.address})]}),e.jsx(t,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(x,{children:e.jsx(c,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:l,onClick:()=>p(!1),children:"Сохранить"})})}),e.jsx(t,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(x,{children:e.jsx(c,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:l,onClick:()=>p(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{D as default};
