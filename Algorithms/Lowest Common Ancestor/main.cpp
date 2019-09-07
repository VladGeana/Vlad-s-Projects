#include <iostream>
#include<fstream>
#include<vector>
#define inf 100000000
using namespace std;
ifstream f("lca.in");
ofstream g("lca.out");
int sz,n,i,x,q,st[500010],tt[100010],lev[100010],a[500010],tree[100010],p_a[100001],y,qls,qld,r[500001][30],lg2[500001];
vector<int>v[100001];
void BuildRMQ()
{
    int i,j;
    for(i=0;i<sz;i++)
        r[i][0]=i;
    int m=lg2[sz]+1;
    for(j=1;j<m;j++)
        for(i=0;i+(1<<j)<=sz;i++)
            if(a[r[i][j-1]] < a[r[i+(1<<(j-1))][j-1]])
                r[i][j]=r[i][j-1];
            else
                r[i][j]=r[i+(1<<(j-1))][j-1];
    //r[i][j]=min(r[i][j-1],r[i+(1<<(j-1))][j-1]);
}
int RMQ(int x,int y)
{
    int l,k;
    l=y-x+1;
    k=lg2[l];
    if(a[r[x][k]] < a[r[x+l-(1<<k)][k]])
        return r[x][k];
    else
        return r[x+l-(1<<k)][k];
    //return min(r[x][k],r[x+l-(1<<k)][k]);
}
void parc_euler(int nod)
{
    st[++sz]=nod;
    for(int k=0;k<v[nod].size();k++)
    {
        parc_euler(v[nod][k]);
        st[++sz]=nod;
    }
}
void dfs(int nod,int niv)
{
    lev[nod]=niv;
    for(int k=0;k<v[nod].size();k++)
        dfs(v[nod][k],niv+1);
}
int main()
{
    f>>n>>q;
    for(i=2;i<=n;i++)
    {
        f>>x;
        tt[i]=x;
        v[x].push_back(i);
    }
    parc_euler(1);
    for(i=1;i<=sz;i++)
        if(!p_a[st[i]])
            p_a[st[i]]=i;
    dfs(1,1);
    for(i=1;i<=sz;i++)
        a[i]=lev[st[i]];
    lg2[1]=0;
    for(i=2;i<=sz;i++)
        lg2[i]=lg2[i/2]+1;
    BuildRMQ();
    for(i=1;i<=q;i++)
    {
        f>>qls>>qld;
        qls=p_a[qls];
        qld=p_a[qld];
        if(qls>qld)
            swap(qls,qld);
        int pmin=RMQ(qls,qld);
        g<<st[pmin]<<'\n';
    }
}
