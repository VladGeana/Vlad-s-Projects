#include <iostream>
#include<fstream>
using namespace std;
ifstream f("hashuri.in");
ofstream g("hashuri.out");
struct nod
{
    int info;
    nod *leg;
};
nod *lprim[666015],*lultim[666015];
void sterge(int info1)
{
    nod *p,*r;
    int rest=info1%666013;
    p=lprim[rest];
    if(p!=NULL&&info1==p->info)
    {
        lprim[rest]=p->leg;
        p->leg=NULL;
        delete p;
    }
    else
    {
        while(p!=NULL && p->leg!=NULL && p->leg->info!=info1) p=p->leg;
        if(p!=NULL && p->leg!=NULL)
        {
            r=p->leg;
            p->leg=r->leg;
            r->leg=NULL;
            delete r;
        }
    }

}
bool gasit(int info1)
{
    nod *p;
    int rest=info1%666013;
    p=lprim[rest];
    if(p!=NULL&&p->info==info1) return 1;
    else while(p!=NULL&&p->info!=info1) p=p->leg;
    if(p!=NULL) return 1;
    else return 0;
}
void creare(nod *lprim[],nod *lultim[])
{
    nod *p;
    int i,n,x,nr,r;
    f>>n;
    for(i=0; i<=666015; i++)
        lprim[i]=lultim[i]=NULL;
    for(i=1; i<=n; i++)
    {
        f>>nr>>x;
        if(nr==1)
        {
            p=new nod;
            p->leg=NULL;
            p->info=x;
            r=x%666013;
                if(lprim[r]==NULL)
                {
                    lprim[r]=p;
                    lultim[r]=p;
                }
                else
                {
                    lultim[r]->leg=p;
                    lultim[r]=p;
                }
        }
        if(nr==2)
            sterge(x);
        if(nr==3) g<<gasit(x)<<'\n';
    }
}
int main()
{
    creare(lprim,lultim);
}
