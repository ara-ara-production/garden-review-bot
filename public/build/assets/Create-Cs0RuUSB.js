import{x as g,J as C,r as v,j as e}from"./app-B2Y_0WYP.js";import{H as f}from"./Head-D6ZDqcuW.js";import{N as w}from"./NavBar-rf5ZZdVX.js";import{C as N,R as y,a,B as u}from"./Alert-DkPuiAaG.js";import{F,a as c}from"./Label-Bape4aym.js";import{R as x,a as h}from"./RowFormGroupSelect-Di-dK1_A.js";import"./Progress-DukPfTwY.js";const D=({users:n})=>{var d,m;const{data:s,setData:t,post:j,processing:i,errors:o}=g({name:"",user_id:(d=n[0])==null?void 0:d.name,two_gis_id:"",pupr_user_id:(m=n[0])==null?void 0:m.name}),{routes:l}=C().props,[b,p]=v.useState(!1),_=r=>{r.preventDefault(),j(`/${l.backendprefix}/${l.brunch}`+(b?"?redirectOnCreation=true":""))};return e.jsxs(e.Fragment,{children:[e.jsx(f,{title:"Создание филиала"}),e.jsx(w,{}),e.jsxs(N,{children:[e.jsx(y,{children:e.jsx(a,{children:e.jsx("h2",{children:"Cоздание филиала"})})}),e.jsxs(F,{className:"row",onSubmit:_,children:[e.jsxs(a,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(x,{label:"Наименование *",inputType:"text",value:s.name,onChange:r=>t("name",r.target.value),error:o.name}),e.jsx(h,{label:"Управляющий",options:n,value:s.user_id,onChange:r=>t("user_id",r.target.value),error:o.user_id}),e.jsx(h,{label:"Помошник управляющего",options:n,value:s.pupr_user_id,onChange:r=>t("pupr_user_id",r.target.value),error:o.pupr_user_id})]}),e.jsx(a,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:e.jsx(x,{label:"id 2Гис филиала",inputType:"text",value:s.two_gis_id,onChange:r=>t("two_gis_id",r.target.value),error:o.two_gis_id})}),e.jsx(a,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(c,{children:e.jsx(u,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:i,onClick:()=>p(!1),children:"Сохранить"})})}),e.jsx(a,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(c,{children:e.jsx(u,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:i,onClick:()=>p(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{D as default};
