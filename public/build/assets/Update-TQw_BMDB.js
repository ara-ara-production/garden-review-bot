import{x as u,J as x,r as h,j as r}from"./app-CCNGmOak.js";import{H as j}from"./Head-w5peUG2M.js";import{N as b}from"./NavBar-muLNc2Y9.js";import{C as _,R as g}from"./Row-BaC-3dof.js";import{C as o,B as f}from"./Alert-BjESWk9v.js";import{F as w,a as C}from"./Label-cSkGvJvA.js";import{R as l,a as N}from"./RowFormGroupSelect-D8KyBZNn.js";const D=({values:s,users:m})=>{const{data:t,setData:n,put:d,processing:p,errors:a}=u({name:s.name??"",user_id:s.user_id??"",two_gis_id:s.two_gis_id??""}),{routes:i}=x().props,[v,y]=h.useState(!1),c=e=>{e.preventDefault(),d(`/${i.backendprefix}/${i.brunch}/${s.id}`)};return r.jsxs(r.Fragment,{children:[r.jsx(j,{title:"Обновление филиала"}),r.jsx(b,{}),r.jsxs(_,{children:[r.jsx(g,{children:r.jsx(o,{children:r.jsx("h2",{children:"Обновление филиала"})})}),r.jsxs(w,{className:"row",onSubmit:c,children:[r.jsxs(o,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[r.jsx(l,{label:"Наименование *",inputType:"text",value:t.name,onChange:e=>n("name",e.target.value),error:a.name}),r.jsx(N,{label:"Управляющий",options:m,value:t.user_id,onChange:e=>n("user_id",e.target.value),error:a.user_id})]}),r.jsx(o,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:r.jsx(l,{label:"id 2Гис филиала",inputType:"text",value:t.two_gis_id,onChange:e=>n("two_gis_id",e.target.value),error:a.two_gis_id})}),r.jsx(o,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:r.jsx(C,{children:r.jsx(f,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:p,children:"Обновить"})})})]})]})]})};export{D as default};
