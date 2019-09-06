#include <iostream>
#include<vector>
#include<cstdio>
using namespace std;
int st[50001],n,m,i,x,y,p,l,cnt;
bool viz[50001];
vector<int>v[50001];

inline void DFS(int nod)
{
    viz[nod]=1;
    if(v[nod].size())
    {
        for(int k=0; k<v[nod].size(); k++)
        {
            if(!viz[v[nod][k]])
                DFS(v[nod][k]);
        }
        p++;
        st[p]=nod;
    }
    else
    {
        p++;
        st[p]=nod;
    }
}
int main()
{
    freopen("sortaret.in","r",stdin);
    freopen("sortaret.out","w",stdout);
    cin>>n>>m;
    for(i=1; i<=m; i++)
    {
        cin>>x>>y;
        v[x].push_back(y);
    }
    for(i=1; i<=n; i++)
        if(!viz[i])
        {
            DFS(i);
        }
    for(i=p; i>=1; i--)
        cout<<st[i]<<' ';

}
