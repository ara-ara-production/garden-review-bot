import{u as x,a as _,r as h,j as e}from"./app-CgCtdgeg.js";import{H as j}from"./Head-BPHojOzv.js";import{N as b}from"./NavBar-BOHFDfjK.js";import{C as g,R as f,a as n,B as w}from"./Alert-COWuk5X9.js";import{F as C,a as v}from"./Label-BkW_CDsj.js";import{R as i,a as l}from"./RowFormGroupSelect-fXYgN-h0.js";import"./Progress-C5njFPRq.js";const A=({values:s,users:d})=>{const{data:a,setData:t,put:u,processing:m,errors:o}=x({name:s.name??"",user_id:s.user_id??"",two_gis_id:s.two_gis_id??"",pupr_user_id:s.pupr_user_id??"",address:s.address??""}),{routes:p}=_().props,[y,F]=h.useState(!1),c=r=>{r.preventDefault(),u(`/${p.backendprefix}/${p.brunch}/${s.id}`)};return e.jsxs(e.Fragment,{children:[e.jsx(j,{title:"Обновление филиала"}),e.jsx(b,{}),e.jsxs(g,{children:[e.jsx(f,{children:e.jsx(n,{children:e.jsx("h2",{children:"Обновление филиала"})})}),e.jsxs(C,{className:"row",onSubmit:c,children:[e.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(i,{label:"Наименование *",inputType:"text",value:a.name,onChange:r=>t("name",r.target.value),error:o.name}),e.jsx(l,{label:"Управляющий",options:d,value:a.user_id,onChange:r=>t("user_id",r.target.value),error:o.user_id}),e.jsx(l,{label:"Помошник управляющего",options:d,value:a.pupr_user_id,onChange:r=>t("pupr_user_id",r.target.value),error:o.pupr_user_id})]}),e.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(i,{label:"id 2Гис филиала",inputType:"text",value:a.two_gis_id,onChange:r=>t("two_gis_id",r.target.value),error:o.two_gis_id}),e.jsx(i,{label:"Адрес (используется для api)",inputType:"text",value:a.address,onChange:r=>t("address",r.target.value),error:o.address})]}),e.jsx(n,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(v,{children:e.jsx(w,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:m,children:"Обновить"})})})]})]})]})};export{A as default};
