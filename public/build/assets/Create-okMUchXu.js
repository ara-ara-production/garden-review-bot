import{x as j,J as b,r as C,j as e}from"./app-CCNGmOak.js";import{H as f}from"./Head-w5peUG2M.js";import{N as g}from"./NavBar-muLNc2Y9.js";import{C as _,R as v}from"./Row-BaC-3dof.js";import{C as s,B as c}from"./Alert-BjESWk9v.js";import{F as w,a as d}from"./Label-cSkGvJvA.js";import{R as p,a as N}from"./RowFormGroupSelect-D8KyBZNn.js";const S=({users:o})=>{const{data:n,setData:a,post:u,processing:i,errors:t}=j({name:"",user_id:o[0].name,two_gis_id:""}),{routes:l}=b().props,[x,m]=C.useState(!1),h=r=>{r.preventDefault(),u(`/${l.backendprefix}/${l.brunch}`+(x?"?redirectOnCreation=true":""))};return e.jsxs(e.Fragment,{children:[e.jsx(f,{title:"Создание филиала"}),e.jsx(g,{}),e.jsxs(_,{children:[e.jsx(v,{children:e.jsx(s,{children:e.jsx("h2",{children:"Cоздание филиала"})})}),e.jsxs(w,{className:"row",onSubmit:h,children:[e.jsxs(s,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(p,{label:"Наименование *",inputType:"text",value:n.name,onChange:r=>a("name",r.target.value),error:t.name}),e.jsx(N,{label:"Управляющий",options:o,value:n.user_id,onChange:r=>a("user_id",r.target.value),error:t.user_id})]}),e.jsx(s,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:e.jsx(p,{label:"id 2Гис филиала",inputType:"text",value:n.two_gis_id,onChange:r=>a("two_gis_id",r.target.value),error:t.two_gis_id})}),e.jsx(s,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(d,{children:e.jsx(c,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:i,onClick:()=>m(!1),children:"Сохранить"})})}),e.jsx(s,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(d,{children:e.jsx(c,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:i,onClick:()=>m(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{S as default};
