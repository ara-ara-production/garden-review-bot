import{x as u,J as x,r as h,j as e}from"./app-CZTnkfrQ.js";import{H as j}from"./Head-Dpg08hJs.js";import{N as b}from"./NavBar-dMrdLGPg.js";import{C as _,R as g,a as t,B as f}from"./Alert-CfoFX0dy.js";import{F as w,a as C}from"./Label-B1tqNK9f.js";import{R as l,a as N}from"./RowFormGroupSelect-DQ-qk43o.js";import"./Progress-CD5bXtNc.js";const D=({values:s,users:d})=>{const{data:a,setData:n,put:m,processing:p,errors:o}=u({name:s.name??"",user_id:s.user_id??"",two_gis_id:s.two_gis_id??""}),{routes:i}=x().props,[v,y]=h.useState(!1),c=r=>{r.preventDefault(),m(`/${i.backendprefix}/${i.brunch}/${s.id}`)};return e.jsxs(e.Fragment,{children:[e.jsx(j,{title:"Обновление филиала"}),e.jsx(b,{}),e.jsxs(_,{children:[e.jsx(g,{children:e.jsx(t,{children:e.jsx("h2",{children:"Обновление филиала"})})}),e.jsxs(w,{className:"row",onSubmit:c,children:[e.jsxs(t,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(l,{label:"Наименование *",inputType:"text",value:a.name,onChange:r=>n("name",r.target.value),error:o.name}),e.jsx(N,{label:"Управляющий",options:d,value:a.user_id,onChange:r=>n("user_id",r.target.value),error:o.user_id})]}),e.jsx(t,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:e.jsx(l,{label:"id 2Гис филиала",inputType:"text",value:a.two_gis_id,onChange:r=>n("two_gis_id",r.target.value),error:o.two_gis_id})}),e.jsx(t,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(C,{children:e.jsx(f,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:p,children:"Обновить"})})})]})]})]})};export{D as default};
