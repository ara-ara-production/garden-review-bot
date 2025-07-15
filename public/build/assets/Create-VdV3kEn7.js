import{x as b,J as _,r as g,j as e}from"./app-CeozcTey.js";import{H as C}from"./Head-Bil4Vv8S.js";import{N as v}from"./NavBar-w2YA9L6O.js";import{C as f,R as w,a,B as d}from"./Alert-DkaYXigQ.js";import{F as N,a as m}from"./Label-D6ApoLzU.js";import{R as u,a as c}from"./RowFormGroupSelect-_xNM69YU.js";import"./Progress-C9k2ca2S.js";const S=({users:n})=>{const{data:s,setData:t,post:x,processing:i,errors:o}=b({name:"",user_id:n[0].name,two_gis_id:"",pupr_user_id:n[0].name}),{routes:l}=_().props,[h,p]=g.useState(!1),j=r=>{r.preventDefault(),x(`/${l.backendprefix}/${l.brunch}`+(h?"?redirectOnCreation=true":""))};return e.jsxs(e.Fragment,{children:[e.jsx(C,{title:"Создание филиала"}),e.jsx(v,{}),e.jsxs(f,{children:[e.jsx(w,{children:e.jsx(a,{children:e.jsx("h2",{children:"Cоздание филиала"})})}),e.jsxs(N,{className:"row",onSubmit:j,children:[e.jsxs(a,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(u,{label:"Наименование *",inputType:"text",value:s.name,onChange:r=>t("name",r.target.value),error:o.name}),e.jsx(c,{label:"Управляющий",options:n,value:s.user_id,onChange:r=>t("user_id",r.target.value),error:o.user_id}),e.jsx(c,{label:"Помошник управляющего",options:n,value:s.pupr_user_id,onChange:r=>t("pupr_user_id",r.target.value),error:o.pupr_user_id})]}),e.jsx(a,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:e.jsx(u,{label:"id 2Гис филиала",inputType:"text",value:s.two_gis_id,onChange:r=>t("two_gis_id",r.target.value),error:o.two_gis_id})}),e.jsx(a,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(m,{children:e.jsx(d,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:i,onClick:()=>p(!1),children:"Сохранить"})})}),e.jsx(a,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(m,{children:e.jsx(d,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:i,onClick:()=>p(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{S as default};
